<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Product;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;
use Event;

class RewardView extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $product;
    private $moduleEnabled;
    public $html;

    public function componentDetails()
    {
        return [
            'name' => 'Reward View',
            'description' => 'View A Reward',
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
        if($this->moduleEnabled){
            $slug = $this->param('product_id');
            $this->product = Product::where('slug','=', $slug)->first();
            if($this->product == null){
                $this->product = Product::find($slug);
            }
            if($this->product){
                $this->product = $this->product->load('categories','images','feature_image');
                $this->productFactory();
                $this->generateHtml();
            }
        }
    }

    /**
        Transform Product in $this->products based on program + user details for rendering.
    **/

    public function productFactory()
    {
        $this->product->name = html_entity_decode($this->product->name);
        $this->product->description = html_entity_decode($this->product->description);
        $useravailablepoints = $this->user->current_points;
        $this->product->points_value = $this->product->points_value * $this->user->currentProgram->scale_points_by;
        $this->product->points_name = $this->user->currentProgram->points_name;
        $this->product->redeemable = false;
        $this->product->can_buy_points = $this->user->currentProgram->can_buy_points;
        if($this->product->can_buy_points == true){
            if($this->user->currentProgram->points_limit < $this->product->points_value){
                $this->product->can_buy_points = false;
            }
        }
        $this->product->percent = ceil(($useravailablepoints / $this->product->points_value * 100));
        $this->product->pointsdif = $this->product->points_value - $useravailablepoints;
        if($this->product->points_value <= $useravailablepoints ){
            $this->product->redeemable = true;
            $this->product->percent = 100;
        }
        if($this->product->external_image_url != null){
            $this->product->imagepath = $this->product->external_image_url;
        } else {
             $this->product->imagepath = $this->product->feature_image->getThumb('auto', 'auto');
        }
    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $currentProgram = $this->user->currentProgram;
        $shipping_message =  $this->user->currentProgram->shipping_message;
        $this->html = $this->renderPartial('@product',
            [
                'product' => $this->product,
                'shipping_message' => $shipping_message,
                'shipping_message_status' => $currentProgram->shipping_message_status
            ]
        );
    }
}
