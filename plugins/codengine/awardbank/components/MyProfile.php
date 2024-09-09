<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Address;
use Codengine\Awardbank\Models\PointsLedger;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Message;
use Codengine\Awardbank\Models\Thankyou;
use Codengine\Awardbank\Models\BillingContact;
use Codengine\Awardbank\Models\Transaction;
use Codengine\Awardbank\Models\Product;
use RainLab\User\Models\User;
use Auth;
use Event;
use Carbon\Carbon;
use Config;
use Responsiv\Uploader\Components\FileUploader;

class MyProfile extends ComponentBase
{
    /** MODELS **/

    private $user;
    private $config;
    private $imagecomponent;
    private $moduleEnabled;
    private $navoptions = [];
    public $navoption;
    private $teams = [];
    private $pointsledgers = [];
    private $billingcontact;
    private $pointsaddition = 0;
    private $pointssubtraction = 0;
    private $startingledger = 0;
    private $orders;
    private $orderstotal = 0;
    private $thankyoucount = 0 ;
    private $messagecount = 0;
    private $inboxcount = 0;
    private $value;
    private $dollarvalue;
    private $limit;
    private $wishlistArray = [];
    private $wishlistProducts;
    public $html1;
    public $html2;

    public function componentDetails()
    {
        return [
            'name'        => 'My Profile',
            'description' => 'My Profile'
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
            $this->imagecomponent = $this->addComponent(
                FileUploader::class,
                'fileUploader',
                ['deferredBinding' => false]
            );
            $this->imagecomponent->bindModel('avatar', User::find($this->user->id));
        }
    }

    public function onRun(){
        $this->addJs('/plugins/codengine/awardbank/assets/js/calendar.min.js');
        $this->addJs('https://api.payway.com.au/rest/v1/payway.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/PayWay.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/MyProfile151019.js');
        $this->config = Config::get('codengine.awardbank::payway');
        if($this->navoption == null){
            $this->navoption = $this->param('navoption');
        }
        $this->imagecomponent->fileList = '';
        $this->imagecomponent->singleFile = '';
        $this->coreLoadSequence();
    }

    /**
        Component Custom Functions
    **/

    /**
        Reusable function call the core sequence of functions to load and render the Cart partial html
    **/

    public function coreLoadSequence()
    {
        $this->setNavOptions($this->navoption);
        if($this->navoption == 'teams'){
            $this->getTeams();
        }
        if($this->navoption == 'messages'){
            $this->getMessages();
        }
        if($this->navoption == 'thankyous'){
            $this->getThankyous();
        }
        if($this->navoption == 'mypoints'){
            $this->getPointsLedgers();
        }
        if($this->navoption == 'myorders'){
            $this->getOrders();
        }
        if($this->navoption == 'billingdetails'){
            $this->getBillingContact();
        }
        if($this->navoption == 'wishlist'){
            $this->getWishlist();
        }
        $this->getCounts();
        $this->generateHtml();
    }

    public function setNavOptions($navoption)
    {
        $this->navoptions = [
            'generaldetails' => 'General Details',
            'billingdetails' => 'Billing Details',
            'shippingaddress' => 'Shipping Address',
            'homeaddress' => 'Home Address',
            //'wishlist' => 'Wishlist', //Requested to be disabled, Ticket EVT-134
            'teams' => $this->user->currentProgram->team_label,
            'messages' => 'Messages',
            'thankyous' => 'Thank You',
            'mypoints' => 'Points',
            'myorders' => 'Orders',
        ];

        $currentProgram = $this->user->currentProgram;
        if (isset($currentProgram->module_allow_users) && $currentProgram->module_allow_users == false) {
            unset($this->navoptions['messages']);
            unset($this->navoptions['thankyous']);
            unset($this->navoptions['teams']);
        }

        if (array_key_exists($navoption, $this->navoptions)) {
            $this->navoption = $navoption;
        } else {
            $this->navoption = 'generaldetails';
        }
    }

    public function getTeams()
    {
        $this->teams = $this->user->teams()->where('program_id','=',$this->user->currentProgram->id)->with('parent','children')->orderBy('id','desc')->get();
        $teamsmanaged = $this->user->teams_manager()->where('program_id','=',$this->user->currentProgram->id)->pluck('id')->toArray();
        $this->teams = $this->teams->each(function($team) use ($teamsmanaged){
            $team->managed = false;
            if(in_array($team->id,$teamsmanaged)){
                $team->managed = true;
            }
            return $team;
        });
    }

    public function getMessages()
    {
        $this->user->receivedmessages = $this->user->receivedmessages()->where('program_id','=',$this->user->currentProgram->id)->orderBy('created_at','desc')->get();
        $this->user->sentmessages = $this->user->sentmessages()->where('program_id','=',$this->user->currentProgram->id)->orderBy('created_at','desc')->get();
    }

    public function getThankyous()
    {
        $this->user->receivedthankyous = $this->user->receivedthankyous()->where('program_id','=',$this->user->currentProgram->id)->orderBy('created_at','desc')->get();
        $this->user->sentthankyous = $this->user->sentthankyous()->where('program_id','=',$this->user->currentProgram->id)->orderBy('created_at','desc')->get();
    }

    public function getPointsLedgers()
    {
        $this->startingledger = PointsLedger::where('user_id','=',$this->user->id)->where('program_id','=',$this->user->current_program_id)->where('type','=',0)->orderBy('id','DESC')->first();
        if($this->startingledger != null){
            $startingledgerid = $this->startingledger->id;
        } else {
            $startingledgerid = 1;
        }
        $this->pointsledgers = $this->user->pointsledgers()->where('program_id','=',$this->user->currentProgram->id)->where('id','>=',$startingledgerid)->where('type','<=',4)->with('user')->orderBy('id','desc')->get();
        $this->pointsledgers = $this->pointsledgers->each(function($pointsledger){
            $pointsledger->points_name = $this->user->currentProgram->points_name;
            if($pointsledger->type == 'Addition Value'){
                $this->pointsaddition = $this->pointsaddition + $pointsledger->points_value;
            }
            if($pointsledger->type == 'Subtraction Value'){
                $this->pointssubtraction = $this->pointsaddition + $pointsledger->points_value;
            }
            return $pointsledger;
        });
    }

    public function getOrders()
    {
        $this->orders = $this->user->orders()->where('order_program_id','=',$this->user->currentProgram->id)->orderBy('id','desc')->get();
        $this->orders = $this->orders->each(function($order){
            $order->points_name = $this->user->currentProgram->points_name;
            $this->orderstotal = $this->orderstotal + $order->points_value;
            return $order;
        });
    }

    public function getBillingContact()
    {
        $this->billingcontact = $this->user->billingcontact;
    }

    public function getWishlist()
    {
        if(is_array($this->user->wishlist_array)){
            $this->wishlistArray = $this->user->wishlist_array;
            if(isset($this->wishlistArray[$this->user->currentProgram->id])){
                $this->wishlistArray = $this->wishlistArray[$this->user->currentProgram->id];
            }
        }
        $this->wishlistProducts = Product::whereIn('id',array_keys($this->wishlistArray))->get();
        $this->wishlistProducts = $this->wishlistProducts->each(function($product){
            if($product->external_image_url != null){
                $product->imagepath = $product->external_image_url;
            } else {
                $product->imagepath = $product->feature_image->getThumb('auto', 'auto');
            }
            return $product;
        });
    }

    public function getCounts()
    {
        $this->messagecount = $this->user->receivedmessages()->where('program_id','=',$this->user->currentProgram->id)->where('read','=',0)->orderBy('created_at','desc')->count();

        $this->thankyoucount = $this->user->receivedthankyous()->where('program_id','=',$this->user->currentProgram->id)->where('read','=',0)->orderBy('created_at','desc')->count();

        $this->inboxcount = $this->messagecount + $this->thankyoucount;
    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@profilelhsnav',
            [
                'navoptions' => $this->navoptions,
                'navoption' => $this->navoption,
                'user' => $this->user,
                'messagecount' => $this->messagecount,
                'thankyoucount' => $this->thankyoucount,
            ]
        );
        $this->html2 = $this->renderPartial('@'.$this->navoption,
            [
                'user' => $this->user,
                'teams' => $this->teams,
                'pointsledgers' => $this->pointsledgers,
                'pointsaddition' => $this->pointsaddition,
                'pointssubtraction' => $this->pointssubtraction,
                'startingledger' => $this->startingledger,
                'orders' => $this->orders,
                'orderstotal' => $this->orderstotal,
                'billingcontact' => $this->billingcontact,
                'publishablekey' => $this->config['publishableAPI'],
                'wishlistproducts' => $this->wishlistProducts,
            ]
        );
    }

    public function testPost($input)
    {
        if($input != '' && !empty($input)){
            return true;
        } else {
            return false;
        }
    }

    /**
        AJAX Requests
    **/

    public function onUpdateTab()
    {
        if($this->testPost(post('navoption')) == true){
            $this->navoption = post('navoption');
            if($this->navoption  == 'billingdetails'){
                $result['paywayrun'] = 1;
            }
            if($this->navoption  == 'generaldetails'){
                $result['fileuploaderrun'] = 1;
            }
        }
        $this->pageCycle();
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        return $result;
    }

    public function onUpdateGeneralDetails()
    {
        try{
            if($this->testPost(post('Company_Position')) == true){
                $this->user->company_position = post('Company_Position');
            }
            if($this->testPost(post('First_Name')) == true){
                $this->user->name = post('First_Name');
            }
            if($this->testPost(post('Surname')) == true){
                $this->user->surname = post('Surname');
            }
            if($this->testPost(post('First_Name')) == true && $this->testPost(post('Surname')) == true){
                $this->user->full_name =  post('First_Name').' '.post('Surname');
            }
            if($this->testPost(post('Business_Name')) == true){
                $this->user->business_name = post('Business_Name');
            }
            if($this->testPost(post('Email')) == true){
                $this->user->email = post('Email');
            }
            if($this->testPost(post('Phone_Number')) == true){
                $this->user->phone_number = post('Phone_Number');
            }
            if($this->testPost(post('Birthday')) == true){
                $this->user->new_birthday = new Carbon(post('Birthday'));
            }
            $this->user->save();
            $this->navoption = 'generaldetails';
            $this->pageCycle();
            $result['fileuploaderrun'] = 1;
            $result['updatesucess'] = "User updated.";
            $result['html']['#html1target'] = $this->html1;
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdateShippingAddress()
    {
        try{
            $address = $this->user->shippingAddress;
            if($address == null){
                $address = new Address;
            }
            $address->business_name = post('Business_Name');
            $address->attn_name = post('Attn__Name');
            $address->unit_number = post('Unit_Number');
            $address->floor = post('Floor');
            $address->street_number = post('Street_Number');
            $address->street_name = post('Street_Name');
            $address->suburb_name = '';
            $address->city = post('City');
            $address->postcode = post('Postcode');
            $address->state = post('State');
            $address->type = 'Shipping';
            $address->country_id = 1;
            $address->save();
            $this->user->shipping_address_id = $address->id;
            $this->user->save();
            $this->user->load('shippingAddress');
            $this->navoption = 'shippingaddress';
            $this->pageCycle();
            $result['updatesucess'] = "Address updated.";
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdateHomeAddress()
    {
        try{
            $address = $this->user->homeAddress;
            if($address == null){
                $address = new Address;
            }
            $address->business_name = post('Business_Name');
            $address->attn_name = post('Attn__Name');
            $address->unit_number = post('Unit_Number');
            $address->floor = post('Floor');
            $address->street_number = post('Street_Number');
            $address->street_name = post('Street_Name');
            $address->suburb_name = '';
            $address->city = post('City');
            $address->postcode = post('Postcode');
            $address->state = post('State');
            $address->type = 'Home';
            $address->country_id = 1;
            $address->save();
            $this->user->home_address_id = $address->id;
            $this->user->save();
            $this->user->load('homeAddress');
            $this->navoption = 'homeaddress';
            $this->pageCycle();
            $result['updatesucess'] = "Address updated.";
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onManageTeam()
    {
        try{
            $team = Team::find(post('id'));
            if($team){
                $this->html2 = $this->renderPartial('@teamupdate',
                    [
                        'team' => $team,
                        'updated' => false,
                    ]
                );
                $result['html']['#html2target'] = $this->html2;
                return $result;
            } else {
                $result['manualerror'] = "Unable to retrieve Team record.";
                return $result;
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdateTeam()
    {
        try{
            $team = Team::find(post('id'));
            if($team){
                if($this->testPost(post('Name')) == true){
                    $team->name = post('Name');
                }
                if($this->testPost(post('External_Reference')) == true){
                    $team->external_reference = post('External_Reference');
                }
                if($this->testPost(post('Primary_Color')) == true){
                    $team->primary_color = post('Primary_Color');
                }
                if($this->testPost(post('Secondary_Color')) == true){
                    $team->secondary_color = post('Secondary_Color');
                }
                if($this->testPost(post('Description')) == true){
                    $team->description = post('Description');
                }
                $team->save();
                $this->navoption = 'teams';
                $this->pageCycle();
                $result['html']['#html2target'] = $this->html2;
                $result['updatesucess'] = "Team updated.";
            } else {
                $result['manualerror'] = "Unable to retrieve Team record.";
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onMessageRead()
    {
        try{
            $message = Message::find(post('id'));
            if($message){
                $message->read = true;
                $message->save();
                $result['updatesucess'] = "Message read.";
            }
            $this->navoption = 'messages';
            $this->pageCycle();
            $result['html']['#html1target'] = $this->html1;
            $result['html']['#html2target'] = $this->html2;
            $result['html']['#inboxcount'] = '<a href="#" class="ui small label circular primary mailbox-count">'.$this->inboxcount.'</a>';
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onAllMessageRead()
    {
        try{
            $messages = $this->user->receivedmessages()->where('program_id','=',$this->user->currentProgram->id)->where('read','=',0)->orderBy('created_at','desc')->get();
            foreach($messages as $message){
                if($message){
                    $message->read = true;
                    $message->save();
                }
            }
            $this->navoption = 'messages';
            $this->pageCycle();
            $result['updatesucess'] = "Messages read.";
            $result['html']['#html1target'] = $this->html1;
            $result['html']['#html2target'] = $this->html2;
            $result['html']['#inboxcount'] = '<a href="#" class="ui small label circular primary mailbox-count">'.$this->inboxcount.'</a>';
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onThankyouRead()
    {
        try{
            $thankyou = Thankyou::find(post('id'));
            if($thankyou){
                $thankyou->read = true;
                $thankyou->save();
                $result['updatesucess'] = "Thankyou read.";
            }
            $this->navoption = 'thankyous';
            $this->pageCycle();
            $result['html']['#html1target'] = $this->html1;
            $result['html']['#html2target'] = $this->html2;
            $result['html']['#inboxcount'] = '<a href="#" class="ui small label circular primary mailbox-count">'.$this->inboxcount.'</a>';
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onAllThankyouRead()
    {
        try{
            $thankyous = $this->user->receivedthankyous()->where('program_id','=',$this->user->currentProgram->id)->where('read','=',0)->orderBy('created_at','desc')->get();
            foreach($thankyous as $thankyou){
                if($thankyou){
                    $thankyou->read = true;
                    $thankyou->save();
                }
            }
            $this->navoption = 'thankyous';
            $this->pageCycle();
            $result['updatesucess'] = "Thank You All Read.";
            $result['html']['#html1target'] = $this->html1;
            $result['html']['#html2target'] = $this->html2;
            $result['html']['#inboxcount'] = '<a href="#" class="ui small label circular primary mailbox-count">'.$this->inboxcount.'</a>';
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdatePaymentMethod()
    {
        $this->pageCycle();
        $client = new \GuzzleHttp\Client();
        $response = post('responsedata');
        $billingContact = BillingContact::find(post('id'));
        if($billingContact == null){
            $billingContact = new BillingContact;
        }
        $billingContact->trackingcategoryname = 'user';
        $billingContact->name = $this->user->full_name.'-'.time();
        $billingContact->firstname = post('First_Name');
        $billingContact->lastname =  post('Surname');
        $billingContact->emailaddress = post('Email');
        $billingContact->defaultCurrent = $response['creditCard']['maskedCardNumber'];
        $billingContact->payment_terms = $response['creditCard']['cardScheme'];
        $billingContact->payway_last_token = $response['singleUseTokenId'];
        $billingContact->save();
        $checkexists = $client->get('https://api.payway.com.au/rest/v1/customers/xtend-'.$billingContact->id,
            [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Accept' => 'application/json',
                ],
                'auth' =>  [$this->config['secretAPI'], ''],
                'http_errors' => false,
            ]
        );
        if($checkexists->getStatusCode() != 200){
            $form_params = $this->config['form_params'];
            $form_params['singleUseTokenId'] = $response['singleUseTokenId'];
            $form_params['customerName'] = $billingContact->name;
            $form_params['emailAddress'] = $billingContact->emailaddress;
            $form_params['phoneNumber'] = post('phonenumber');
            $res = $client->put('https://api.payway.com.au/rest/v1/customers/xtend-'.$billingContact->id,
                [
                    'headers' => [
                        'Cache-Control' => 'no-cache',
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'auth' =>  [$this->config['secretAPI'], ''],
                    'form_params' => $form_params,
                ]
            );
        } else {
            $form_params = $this->config['form_params'];
            $form_params['singleUseTokenId'] = $response['singleUseTokenId'];
            $res = $client->put('https://api.payway.com.au/rest/v1/customers/xtend-'.$billingContact->id.'/payment-setup',
                [
                    'headers' => [
                        'Cache-Control' => 'no-cache',
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'auth' =>  [$this->config['secretAPI'], ''],
                    'form_params' => $form_params,
                ]
            );
        }
        $this->user->billingcontact_id = $billingContact->id;
        $this->user->save();
        $this->user->load('billingcontact');
        $this->navoption = 'billingdetails';
        $this->pageCycle();
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        $result['updatesucess'] = "Billing Details Updated.";
        $result['paywayrun'] = 1;
        return $result;
    }
}
