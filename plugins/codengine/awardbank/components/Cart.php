<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Product;
use Codengine\Awardbank\models\PointsLedger;
use Auth;
use Event;

class Cart extends ComponentBase
{

    /** MODELS **/

    public $user;
    private $points_name;
    private $shippingAddress;
    private $points_scale;
    private $products;
    private $navproducts = [];
    private $orderproducts = [];
    private $productscount = 0;
    private $cartArray = [];
    private $wishlistArray = [];
    private $orderCartArray = [];
    private $mypoint = 0;
    private $mypending = 0;
    private $orderTotal = 0;
    private $inboxcount = 0;
    private $moduleEnabled;
    private $accessRelation;
    private $customaddition;
    private $wishlistProducts;
    public $cartnavhtml;
    public $cartnavmobilehtml;
    public $orderviewhtml;

    public function componentDetails()
    {
        return [
            'name' => 'Cart Component',
            'description' => 'Current Users Cart',
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
            $this->user =  $this->user->load(['currentProgram','avatar','shippingAddress']);
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
            $this->addJs('/plugins/codengine/awardbank/assets/js/Cart281119.js');
            $this->coreLoadSequence('generic');
        }
    }

    /**
        Component Custom Functions
    **/

    /**
        Reusable function call the core sequence of functions to load and render the Cart partial html
    **/

    public function coreLoadSequence($page)
    {
        $this->loadMyCart();
        $this->loadMyProducts();
        $this->user->runMyPoints();
        $this->loadCartCurrentPointsState();
        $this->loadInboxCount();
        if($page == 'OrderCreate'){
            $this->checkMyShippingAddress();
            $this->orderProductsFactory();
        }
        $this->generateHtml($page);
    }

    /**
        Load the current points state for the Cart into the component variables from the stored user JSON
    **/

    protected function loadCartCurrentPointsState(){
        if(is_array($this->user->points_json)){
            if(isset($this->user->points_json[$this->user->current_program_id])){
                $this->mypoint = $this->user->current_points;
                $this->mypending = $this->user->pending_points;
            }
        }
    }

    /**
        Load the current products stored in the users Cart Json in the database
    **/

    protected function loadMyCart(){
        if(is_array($this->user->cart_array)){
            $this->cartArray = $this->user->cart_array;
        }
        if(is_array($this->user->wishlist_array)){
            $this->wishlistArray = $this->user->wishlist_array;
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
        Fetch products based on $this->cartArray keys
        Calculate the total value of the order by summing products
        Count the total number of products
    **/

    protected function loadMyProducts(){
        $this->productscount = 0;

        if(isset($this->cartArray[$this->user->currentProgram->id]) && is_array($this->cartArray[$this->user->currentProgram->id])){
            $orderArray = [];
            foreach($this->cartArray[$this->user->currentProgram->id] as $key => $value){
                $product = Product::find($key);
                if($product){
                    $orderArray[] = $product;
                } else {
                    $product = Product::where('id','=',$key)->withTrashed()->first();
                    unset($this->cartArray[$this->user->currentProgram->id][$key]);
                    $this->user->cart_array = $this->cartArray;
                    $this->user->save();
                    $this->createPointsLedger($product->points_value * $this->points_scale,$product->id,4);
                }
            }

            $this->products = collect($orderArray);
            $this->orderTotal = $this->products->sum(function ($product) {
                return $product->points_value * $this->cartArray[$this->user->currentProgram->id][$product->id]['volume'];
            });
            $this->orderTotal = $this->orderTotal * $this->points_scale;
            $this->navproducts = $this->products->map(function ($product) {
                return $this->productFactory($product, $this->cartArray[$this->user->currentProgram->id][$product->id]);;
            });
        }
    }

    /**
        Fetch count of users unread Thankyous and Messages
    **/

    protected function loadInboxCount()
    {
        $this->inboxcount = $this->inboxcount + $this->user->receivedmessages()->where('program_id','=',$this->user->currentProgram->id)->where('read','=',0)->orderBy('created_at','desc')->count();
        $this->inboxcount = $this->inboxcount + $this->user->receivedthankyous()->where('program_id','=',$this->user->currentProgram->id)->where('read','=',0)->orderBy('created_at','desc')->count();
    }

    /**
        Factory for merging the variable options per product in the the displayable collection
    **/

    protected function productFactory($product,$options)
    {
        if($product->external_image_url != null){
            $product->imagepath = $product->external_image_url;
        } else {
             $product->imagepath = $product->feature_image ? $product->feature_image->getThumb('auto', 'auto') : '';
        }
        $product->points_value = $product->points_value * $this->points_scale;
        if(isset($options['volume'])){
            $product->volume = $options['volume'];
            $this->productscount = $this->productscount + $options['volume'];
        }
        return $product;
    }

    /**
        Factory to add options values to Product Information In Cart
    **/

    public function orderProductsFactory()
    {
        $orderArray = [];
        if(isset($this->cartArray[$this->user->currentProgram->id]) && is_array($this->cartArray[$this->user->currentProgram->id])){
            foreach($this->cartArray[$this->user->currentProgram->id] as $row){
                $x = 1;
                do {
                    $product = Product::find($row['id']);
                    if($product){
                        $orderArray[] = $product;
                    }
                    $x++;
                } while ($x <= $row['volume']);
            }
        }
        $this->orderCartArray = collect($orderArray);
        $this->orderproducts = $this->orderCartArray->map(function($product){
            if($product->external_image_url != null){
                $product->imagepath = $product->external_image_url;
            } else {
                $product->imagepath = $product->feature_image->getThumb('auto', 'auto');
            }
            $product->points_value = $product->points_value * $this->points_scale;
            $product->options1 = $this->optionsProcessor($product->options1);
            $product->options2 = $this->optionsProcessor($product->options2);
            $product->options3 = $this->optionsProcessor($product->options3);
            return $product;
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

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml($page)
    {
        $this->cartnavhtml = $this->renderPartial('@cartnavhtml',
            [
                'products' => $this->navproducts,
                'ordertotal' => $this->orderTotal,
                'myavailable' => $this->mypoint,
                'mypending' => $this->mypending,
                'inboxcount' => $this->inboxcount,
                'count' => $this->productscount,
                'points_name' => $this->points_name,
                'user' => $this->user,
            ]
        );
        $this->cartnavmobilehtml = $this->renderPartial('@cartnavmobilehtml',
            [
                'myavailable' => $this->mypoint,
                'points_name' => $this->points_name,
            ]
        );
        if($page == 'OrderCreate'){
            $this->orderviewhtml = $this->renderPartial('@orderform',
                [
                    'products' => $this->orderproducts,
                    'shippingAddress' => $this->shippingAddress,
                    'ordertotal' => $this->orderTotal,
                    'points_name' => $this->points_name,
                ]
            );
        }
    }

    /**
        Check that the amount of available points is less than the point cost of the product to be added to the cart.

        @param integer $amount A numerical value for product points value * the current programs points scaling.
    **/

    public function checkCanAfford($amount)
    {
        if($amount <= $this->mypoint){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check whether the product is present in the cart
     *
     * @param $product
     */
    public function checkIsInCart($product)
    {
        return isset($this->user->cart_array[$this->user->currentProgram->id][$product->id]);
    }

    /**
        Add or Subtract the Product to the users stored cart value
        Create the required variable view elements for that product to display in the cart list.

        @param Product $product a product model
        @param string $isPlus a boolean to indicate if adding or subtracting value
     **/

    public function cartProductFactory($product,$isPlus)
    {
        if($isPlus){
            if(isset($this->cartArray[$this->user->currentProgram->id][$product->id])){
                $this->cartArray[$this->user->currentProgram->id][$product->id]['volume'] = $this->cartArray[$this->user->currentProgram->id][$product->id]['volume'] + 1;
            } else {
                $this->cartArray[$this->user->currentProgram->id][$product->id]['id'] = $product->id;
                $this->cartArray[$this->user->currentProgram->id][$product->id]['volume'] = 1;
            }

            $inCartType = 3;
            $this->createPointsLedger($product->points_value * $this->points_scale,$product->id, $inCartType);
        } else {
            if(isset($this->cartArray[$this->user->currentProgram->id][$product->id])){
                if($this->cartArray[$this->user->currentProgram->id][$product->id]['volume'] >= 2){
                    $this->cartArray[$this->user->currentProgram->id][$product->id]['volume'] = $this->cartArray[$this->user->currentProgram->id][$product->id]['volume'] - 1;
                } else {
                    unset($this->cartArray[$this->user->currentProgram->id][$product->id]);
                }
            }

            $outCartType = 4;
            $this->createPointsLedger($product->points_value * $this->points_scale,$product->id, $outCartType);
        }
    }

    /**
        Add or Subtract the Product to the users stored wishlist
        Create the required variable view elements for that product to display in the wishlist list.

        @param Product $product a product model
        @param string $plusminus a '+' or '-' string to indicate if adding or subtracting value
    **/

    public function cartWishlistFactory($product,$plusminus)
    {
        if($plusminus == '+'){
            if(isset($this->wishlistArray[$this->user->currentProgram->id][$product->id])){
                $this->wishlistArray[$this->user->currentProgram->id][$product->id]['volume'] =
                    $this->wishlistArray[$this->user->currentProgram->id][$product->id]['volume'] + 1;

                $this->wishlistArray[$this->user->currentProgram->id][$product->id]['affordable'] =
                    $this->checkCanAfford(
                        $this->wishlistArray[$this->user->currentProgram->id][$product->id]['volume']
                        //* ($product->points_value * $this->points_scale)
                    ) ? 'yes' : 'no';

            } else {
                $this->wishlistArray[$this->user->currentProgram->id][$product->id]['id'] = $product->id;
                $this->wishlistArray[$this->user->currentProgram->id][$product->id]['volume'] = 1;

                $this->wishlistArray[$this->user->currentProgram->id][$product->id]['affordable'] =
                    $this->checkCanAfford($product->points_value * $this->points_scale) ? 'yes' : 'no';
            }
        } else {
            if(isset($this->wishlistArray[$this->user->currentProgram->id][$product->id])){
                unset($this->wishlistArray[$this->user->currentProgram->id][$product->id]);
            }
        }
    }

    public function createPointsLedger($value,$productid,$type)
    {
        $newLedger = new PointsLedger;
        $newLedger->points_value = $value;
        $newLedger->user_id = $this->user->id;
        $newLedger->program = $this->user->currentProgram->id;
        $newLedger->product_id = $productid;
        $newLedger->type = $type;
        $newLedger->save();
    }

    /**
        AJAX Requests
    **/

    /**
        Function to Add a product from the stored Cart Array
    **/

    public function onAddToCart()
    {
        $page = 'generic';
        if($this->testPost(post('page')) == true){
            $page = post('page');
        }
        $product = Product::find(post('productID'));
        if($product){
            $this->coreLoadSequence($page);
            if($this->checkCanAfford($product->points_value * $this->points_scale)){
                $this->cartProductFactory($product, true);
                $this->user->cart_array = $this->cartArray;
                $this->user->save();
            } else {
                $result['manualerror'] = "Not enough points available to add this item to your cart.";
            }
            $this->coreLoadSequence($page);
            $result['html']['#cartnavhtmltarget'] = $this->cartnavhtml;
            $result['html']['#cartnavmobilehtmltarget'] = $this->cartnavmobilehtml;
            $result['html']['#orderplace-htmltarget'] = $this->orderviewhtml;
            return $result;
        }
    }

    /**
        Function to Remove a product from the stored Cart Array
    **/

    public function onRemoveFromCart()
    {
        $page = 'generic';
        if($this->testPost(post('page')) == true){
            $page = post('page');
        }
        $product = Product::find(post('productID'));
        if($product){
            $this->coreLoadSequence($page);
            if($this->checkIsInCart($product)) {
                $this->cartProductFactory($product, false);
                $this->user->cart_array = $this->cartArray;
                $this->user->save();
            } else {
                $result['manualerror'] = "Product can't be removed as it's no longer present in the cart.";
            }
            $this->coreLoadSequence($page);
            $result['html']['#cartnavhtmltarget'] = $this->cartnavhtml;
            $result['html']['#cartnavmobilehtmltarget'] = $this->cartnavmobilehtml;
            $result['html']['#orderplace-htmltarget'] = $this->orderviewhtml;
            return $result;
        }
    }

    /**
        Function to Add a product from the stored Wishlist Array
    **/

    public function onAddToWishlist()
    {
        $page = 'generic';
        if($this->testPost(post('page')) == true){
            $page = post('page');
        }
        $product = Product::find(post('productID'));
        if($product){
            $this->coreLoadSequence($page);
            $this->cartWishlistFactory($product,'+', null);
            $this->user->wishlist_array = $this->wishlistArray;
            $this->user->save();
            $this->coreLoadSequence($page);
            $this->getWishlist();
            $result['updatesucess'] = $product->name.' added to wishlist.';
            $result['html']['.wishlisttarget'] = $this->renderPartial('@wishlistlist',
                [
                    'products' => $this->wishlistProducts,
                    'points_name' => $this->points_name,
                ]
            );
            return $result;
        }
    }

    /**
        Function to Remove a product from the stored Wishlist Array
    **/

    public function onRemoveFromWishlist()
    {
        $page = 'generic';
        if($this->testPost(post('page')) == true){
            $page = post('page');
        }
        $product = Product::find(post('productID'));
        if($product){
            $this->coreLoadSequence($page);
            $this->cartWishlistFactory($product,'-', null);
            $this->user->wishlist_array = $this->wishlistArray;
            $this->user->save();
            $this->coreLoadSequence($page);
            $this->getWishlist();
            $result['updatesucess'] = $product->name.' removed from wishlist.';
            $result['html']['.wishlisttarget'] = $this->renderPartial('@wishlistlist',
                [
                    'products' => $this->wishlistProducts,
                    'points_name' => $this->points_name,
                ]
            );
            return $result;
        }
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
                $product->imagepath = $product->feature_image ? $product->feature_image->getThumb('auto', 'auto') : '';
            }
            $product->points_value = $product->points_value * $this->points_scale;
            return $product;
        });
    }

    public function testPost($input)
    {
        if($input != '' && !empty($input)){
            return true;
        } else {
            return false;
        }
    }

    function onAcceptTandC(){
        $this->user = Auth::getUser();
        $this->user->t_and_c_accept = true;
        $this->user->save();
    }
}
