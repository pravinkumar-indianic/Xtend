<?php namespace Codengine\Awardbank\Components;

use Addgod\MandrillTemplate\Mandrill\Message;
use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Order;
use Codengine\Awardbank\Models\Product;
use Codengine\Awardbank\Models\Address;
use Codengine\Awardbank\models\OrderLineitem as LineItem;
use System\Helpers\DateTime;
use Auth;
use Event;
use Mail;
use Db;
use Illuminate\Support\Facades\Log;

class OrderCreate extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $moduleEnabled;
    private $points_name;
    private $points_scale;
    private $cartArray;
    private $shippingAddress;
    private $orderTotal;
    private $products;
    public $orderplacehtml;

    public function componentDetails()
    {
        return [
            'name' => 'Order Placing Functionality',
            'description' => 'Order Placing Functionality',
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }


    public function init()
    {

        $this->user = Auth::getUser();
        if($this->user){
            $this->user =  $this->user->load('currentProgram');
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_rewards'], true);
            if($this->user->currentProgram){
                $this->points_name = $this->user->currentProgram->points_name;
                $this->points_scale = $this->user->currentProgram->scale_points_by;
            } else {
                $this->points_name = 'Points';
                $this->points_scale = 1;
            }
        }
    }

    public function onRun()
    {
        if($this->moduleEnabled == true && $this->user){
            $this->addJs('/plugins/codengine/awardbank/assets/js/OrderCreate150819.js');
            $this->coreLoadSequence();
        }
    }

    /**
        Component Custom Functions
    **/

    /**
        Reusable function call the core sequence of functions to load and render the Order partial html
    **/

    public function coreLoadSequence()
    {

        $this->loadMyCart();
        $this->calculateOrderTotal();
        $this->checkMyShippingAddress();
        $this->cartProductsFactory();
        $this->renderPartialToHtml('orderpage');
    }

    /**
        Load the current products stored in the users Cart Json in the database
    **/

    protected function loadMyCart(){
        if(is_array($this->user->cart_array)){
            $this->cartArray = $this->user->cart_array;
            if(isset($this->cartArray[$this->user->currentProgram->id])){
                $this->cartArray = $this->cartArray[$this->user->currentProgram->id];
            }
        }
    }

    /**
        Grab Hydrated Product Models From the DB and Calculate The Order Total
    **/

    private function calculateOrderTotal()
    {
        if(isset($this->cartArray)){
            $this->products = Product::whereIn('id',array_keys($this->cartArray))->get();
            $this->orderTotal = $this->products->sum(function ($product) {
                return $product->points_value * $this->cartArray[$product->id]['volume'];
            });
            $this->orderTotal = $this->orderTotal * $this->points_scale;
        }
    }

    /**
        Get The Users Shipping Address (Old And New Ways - Remove Old When Deprecated)
    **/

    private function checkMyShippingAddress()
    {

        $this->shippingAddress = $this->user->shippingAddress;
        if($this->shippingAddress == null){
            $this->shippingAddress = $this->user->getAddressByType('shipping');
        }
    }

    /**
        Factory to add options values to Product Information In Cart
    **/

    public function cartProductsFactory()
    {
        $orderArray = [];
        if(is_array($this->cartArray)){
            foreach($this->cartArray as $row){
                if (isset($row['id'])) {
                    $x = 1;
                    do {
                        $orderArray[] = Product::find($row['id']);
                        $x++;
                    } while ($x <= $row['volume']);
                }
            }
        }
        $this->cartArray = collect($orderArray);
        $this->cartArray = $this->cartArray->map(function($product){
            if($product){
                $product->imagepath = '';
                if(isset($product->feature_image)){
                    $product->imagepath = $product->feature_image->getThumb('auto', 'auto');
                } elseif(isset($product)) {
                    $product->imagepath = $product->external_image_url;
                }
                $product->points_value = $product->points_value * $this->points_scale;
                $product->options1 = $this->optionsProcessor($product->options1);
                $product->options2 = $this->optionsProcessor($product->options2);
                $product->options3 = $this->optionsProcessor($product->options3);
                return $product;
            }
        });
    }

    /**
        Factory translate Options into Array for Dropdown Display
    **/

    public function optionsProcessor($options)
    {
        if(!empty($options)){
            $result = [];
            foreach($options as $option){
                $result[] = $option['option_name'];
            }
            return $result;
        } else {
            return null;
        }
    }

    public function renderPartialToHtml($type)
    {
        if($type === 'orderpage'){
            $this->orderplacehtml = $this->renderPartial('@orderform',
                [
                    'products' => $this->cartArray,
                    'shippingAddress' => $this->shippingAddress,
                    'ordertotal' => $this->orderTotal,
                    'points_name' => $this->points_name,
                    'phone_number' => $this->user->phone_number,
                ]
            );
        } elseif($type === 'success') {
            $this->orderplacehtml = $this->renderPartial('@success',
                [
                    'user' => $this->user,
                ]
            );
        }
    }

    public function onCreateOrder()
    {
        $this->loadMyCart();
        $this->calculateOrderTotal();
        try {
                $address = new Address;
                $address->business_name = post('Business_Name');
                $address->attn_name = post('Attn__Name');
                $address->unit_number = post('Unit_Number');
                $address->floor = post('Floor');
                $address->street_number = post('Street_Number');
                $address->street_name = post('Street_Name');
                $address->suburb_name = '';
                $address->phone_number = post('PhoneNumber');

                // Also update user phone number
                $this->user->phone_number = post('PhoneNumber');
                $this->user->save();

                $address->city = post('City');
                $address->postcode = post('Postcode');
                $address->state = post('State');
                $address->type = 'Shipping';
                $address->country_id = post('CountryId');
                $address->country = post('Country');
                $address->save();
                if(post('Store_Address') == 'on'){
                    $this->user->shipping_address_id = $address->id;
                    $this->user->save();
                }

                $order = new Order();
                $order->user_id = $this->user->id;
                $order->customer_full_name_string = $this->user->full_name;
                $order->customer_full_email_string = $this->user->email;
                $order->customer_full_phone_number_string = $this->user->phone_number;
                $order->order_program_id = $this->user->current_program_id;
                $order->order_region_id = $this->user->current_region_id;
                $order->order_team_id = $this->user->current_team_id;
                $program = $this->user->currentProgram;
                if ($program->address){
                    $order->order_program_address = $program->address->full_address;
                } else {
                    $order->order_program_address = 'unset';
                }
                if ($program->login_image){
                    $order->order_program_image_url = $program->login_image->path;
                } else {
                    $order->order_program_image_url = null;
                }
                $order->order_program_name = $program->name;
                $order->order_program_points_name = $this->points_name;
                $order->order_program_points_multiple_type = $program->program_markup_type;
                $order->order_program_points_multiple_integer = $this->points_scale;
                $order->shipping_address_id = $address->id;
                $order->current_shipping_addr_string = $address->full_address;
                $order->points_value = $this->orderTotal;
                $order->dollar_value = $this->orderTotal / $this->points_scale;
                $order->points_value_delivered = 0;
                $order->full_processed = 0;
                $order->overall_order_status = 0;
                $order->order_has_vouchers = 0;
                $order->order_full_redeemed_vouchers = 0;
                $order->save();

                foreach($this->cartArray as $key => $value){

                    $product = Product::find($key);

                    $i = 1;

                    while($i <= $value['volume']){

                        $order->products()->attach($product->id);
                        if($product->feature_image){
                            $feature_image = $product->feature_image->path;
                        } elseif($product->image_valid = 1) {
                             $feature_image = $product->external_image_url;
                        } else {
                            $feature_image = null;
                        }
                        if($product->supplier){
                            $supplier_id = $product->supplier->id;
                            $supplier_name = $product->supplier->name;
                        } else {
                            $supplier_id = null;
                            $supplier_name = null;
                        }
                        $lineitem = new LineItem;
                        $lineitem->order_id = $order->id;
                        $lineitem->product_id =  $product->id;
                        $lineitem->product_name =  $product->name;
                        $lineitem->product_image =  $feature_image;
                        $lineitem->product_dollar_value =  $product->points_value;
                        $lineitem->product_slug =  $product->slug;
                        $lineitem->product_deliver_base = $product->deliver_base;
                        $lineitem->product_category_array =  $product->category_array;
                        $lineitem->product_supplier_id =  $supplier_id;
                        $lineitem->product_supplier_name =  $supplier_name;
                        $lineitem->product_status =  0;
                        $lineitem->product_voucher =  0;
                        $lineitem->product_voucher_code =  '';
                        $lineitem->option1_selection = $product->color;
                        $lineitem->option2_selection = $product->size;
                        $lineitem->option3_selection = $product->custom_option;
                        $lineitem->product_volume = 1;
                        $lineitem->order_customer_email = $product->order_customer_email;
                        $lineitem->shipped_date = null;
                        $lineitem->notes = '';
                        $lineitem->save();
                        $i++;
                    }
                }

                $this->user->cart_array = [];
                $this->user->runMyPoints();
                $this->sendOrderEmail($order);

        } catch (\Exception $ex) {
            Log::debug($ex);
            return $ex;
        }
        $this->renderPartialToHtml('success');
        $result['html']['#orderplace-htmltarget'] = $this->orderplacehtml;
        return $result;
    }

    public function sendOrderEmail($order)
    {
        // EMAIL SETUP

        $backendUsers = [];
        //$roles = [1,3,4];
        //$backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();
        $backendUsers[] = 'matthew@codengine.com.au';
        $backendUsers[] = 'joshua@evtmarketing.com.au';
        $backendUsers[] = 'hq@evtmarketing.com.au';

        //$currentProgram = $this->user->currentProgram;

        $toemail = $this->user->email;
        //$toemail = 'hq@xtendsystem.com';
        $programname = $order->order_program_name;
        $programpointsname =  $order->order_program_points_name;
        $createdat = $order->created_at;
        $program_image_url = $order->order_program_image_url;
        $userpointsvalue = $this->user->current_points;
        $title = 'Congratulations '.$this->user->full_name.'. Your Rewards Order has Been Placed';
        $receiverfullname = $this->user->full_name;
        $receiverfirstname = $this->user->name;
        $receiversurname = $this->user->surname;
        $customerfullemailstring = $this->user->email;
        $ordertable = '<table class="ui celled table" class="auto" style="width:100%;text-align:center";>';
        $ordertable .= '<thead><tr><th style="width:50%;text-align:center;padding:10px;">Reward</th><th style="width:50%;text-align:center;padding:10px;">Value</th></tr></thead><tbody>';
        foreach($order->orderlineitems as $lineitem){
            $ordertable .= '<tr><td style="width:50%;text-align:center;padding:10px;">'.$lineitem->product->name.'</td><td style="width:50%;text-align:center;padding:10px;">'.($lineitem->product_dollar_value*$this->user->currentProgram->scale_points_by).'</td>';
        }
        $ordertable .= '</tbody></table></div>';
        $businessname = $order->shippingaddress->business_name;
        $attnname = $order->shippingaddress->attn_name;
        $shippinglevel = $order->shippingaddress->floor;
        $shippingunit = $order->shippingaddress->unit_number;
        $shippingaddress = $order->shippingaddress->street_number.' '.$order->shippingaddress->street_name.' '.$order->shippingaddress->street_type.' '.$order->shippingaddress->city.' '.$order->shippingaddress->suburb_name.' '.$order->shippingaddress->postcode.' '.$order->shippingaddress->state.' '.$order->shippingaddress->country;
        $orderid = $order->id;

        if ($this->user->currentProgram->order_placed_template != null
            && $this->user->currentProgram->order_placed_template != ''
            && $this->user->currentProgram->order_placed_template != 1
        ) {
            $template = $this->user->currentProgram->order_placed_template;
        } else {
            $template = 'xtenddefault-orderplaced-xtend-2-0';
        }

        if ($this->user->currentProgram->order_placed_send == true) {
            $vars = [
                'programname' => $programname,
                'pointsname' => $programpointsname,
                'pointsvalue' => $userpointsvalue,
                'programimageurl' => $program_image_url,
                'title' => $title,
                'ordertable' => $ordertable,
                'receiverfullname' => $receiverfullname,
                'receiverfirstname' => $receiverfirstname,
                'receiversurname' => $receiversurname,
                'customerfullemailstring' => $customerfullemailstring,
                'createdat' => $createdat,
                'businessname'=> $businessname,
                'attnname' => $attnname,
                'shippinglevel' => $shippinglevel,
                'shippingunit' => $shippingunit,
                'shippingaddress' => $shippingaddress,
                'orderid' => $orderid
            ];

            $template = new Template($template);
            $message = new Message();
            $message->setSubject($title);
            $message->setFromEmail('noreply@xtendsystem.com');
            $message->setMergeVars($vars);

            $recipient = new Recipient($toemail, null, Recipient\Type::TO);
            $recipient->setMergeVars($vars);
            $message->addRecipient($recipient);

            if (!empty($backendUsers)) {
                foreach ($backendUsers as $ccemail) {
                    $recipient = new Recipient($ccemail, null, Recipient\Type::CC);
                    $recipient->setMergeVars($vars);
                    $message->addRecipient($recipient);
                }
            }

            MandrillTemplateFacade::send($template, $message);
        }
    }
}
