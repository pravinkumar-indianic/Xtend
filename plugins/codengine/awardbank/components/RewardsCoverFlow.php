<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Category;
use Codengine\Awardbank\models\Product;
use Codengine\Awardbank\models\Program;
use Auth;
use Event;
use Session;
use System\Helpers\DateTime;
use Carbon\Carbon;
use DB;

class RewardsCoverFlow extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $moduleEnabled;
    private $products;
    public $html;

    public function componentDetails()
    {
        return [
            'name' => 'Products List Cover Flow',
            'description' => 'Products List Cover Flow',
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
            $this->user = $this->user->load('currentProgram');
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_rewards'], true);
        }
    }

    public function onRun()
    {
        if($this->moduleEnabled == true && $this->user){
            $this->addAssets();
            $this->getProducts();
            $this->productsFactory();
            $this->generateHtml();
        }
    }

    public function addAssets()
    {
        $this->addCss('/plugins/codengine/awardbank/assets/slick-1.8.0/slick/slick.css');
        $this->addCss('/plugins/codengine/awardbank/assets/slick-1.8.0/slick/slick-theme.css');
        $this->addJs('/plugins/codengine/awardbank/assets/slick-1.8.0/slick/slick.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/RewardsCoverFlow150819.js');
    }

    /**
        Retrieve a random set of 6 valid products
    **/

    public function getProducts()
    {
        $this->products = Product::
        isValidForUser($this->user)
        ->select('codengine_awardbank_products.*', DB::raw('count(*) as total'))
        ->groupBy('codengine_awardbank_products.id')
        ->inRandomOrder()->limit(6)->get();
    }

    /**
        Transform Product in $this->products based on program + user details for rendering.
    **/

    public function productsFactory()
    {
        $this->products = $this->products->each(function ($product, $key) {
            $product->name = html_entity_decode($product->name);
            $product->description = html_entity_decode($product->description);
            $useravailablepoints = $this->user->current_points;
            $product->points_value = $product->points_value * $this->user->currentProgram->scale_points_by;
            $product->points_name = $this->user->currentProgram->points_name;
            $product->redeemable = false;
            $product->can_buy_points = $this->user->currentProgram->can_buy_points;
            $product->percent = ceil(($useravailablepoints / $product->points_value * 100));
            $product->pointsdif = $product->points_value - $useravailablepoints;
            if($product->can_buy_points == true){
                if($this->user->currentProgram->points_limit >= 1){
                    if($this->user->currentProgram->points_limit < $product->pointsdif){
                        $product->can_buy_points = false;
                    }
                }
            }
            if($product->points_value <= $useravailablepoints ){
                $product->redeemable = true;
                $product->percent = 100;
            }
            if($product->external_image_url != null){
                $product->imagepath = $product->external_image_url;
            } else {
                 //$product->imagepath = $product->feature_image->getThumb('auto', 'auto');
            }
            return $product;
        });
    }

    /**
        Render the ProductsLists partial based on post factory array in $this->products
    **/

    public function generateHtml()
    {
        $this->html = $this->renderPartial('@productslist',
            [
                'products' => $this->products,
            ]
        );
    }
}
