<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Order;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;

class OrderView extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $container;
    public $flushtop;
    public $flushbottom;
    public $order;

    public function componentDetails()
    {
        return [
            'name' => 'Order View',
            'description' => 'View A Order',
        ];
    }

    public function defineProperties()
    {
        return [
            'container' => [
                'title' => 'Wrap Component In The Container',
                'type'=> 'checkbox',
                'default' => false,
            ],   
            'flushtop' => [
                'title' => 'Remove Top Padding',
                'type'=> 'checkbox',
                'default' => false,
            ], 
            'flushbottom' => [
                'title' => 'Remove Bottom Padding',
                'type'=> 'checkbox',
                'default' => false,
            ], 
        ];
    }


    public function init()
    {

        $this->user = Auth::getUser();

    }

    public function onRun()
    {
        $this->container = $this->property('container');
        $this->flushtop = $this->property('flushtop');
        $this->flushbottom = $this->property('flushbottom');
        $this->order = Order::where('id','=',$this->param('order_id'))->with('orderlineitems.product','shippingaddress')->first();
    }
}
