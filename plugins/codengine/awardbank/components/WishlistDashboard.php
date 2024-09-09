<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Product;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Event;

class WishlistDashboard extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $wishlistArray = [];
    private $wishlistProducts;
    private $moduleEnabled;
    private $pointsname;
    private $points_scale;
    public $html1;


    public function componentDetails()
    {
        return [
            'name' => 'Wishtlist On Dashboard',
            'description' => 'Wishtlist On Dashboard',
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
        $this->coreLoadSequence();
    }

    public function coreLoadSequence()
    {
        $this->getWishlist();
        $this->generateHtml();
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
                //$product->imagepath = $product->feature_image->getThumb('auto', 'auto');
            }
            $product->points_value = $product->points_value * $this->points_scale;
            return $product;
        });
    }

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@wishlistlist',
            [
                'user' => $this->user,
                'products' => $this->wishlistProducts,
                'points_name' => $this->user->currentProgram->points_name,
            ]
        );
    }
}
