<?php namespace Codengine\Awardbank\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Orders extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        'Backend.Behaviors.RelationController',  
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $importExportConfig = 'config_import_export.yaml'; 
    public $relationConfig = 'config_relation.yaml';         

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Codengine.Awardbank', 'OrdersMain', 'Orders');
    }

    public function onCreatePartialUpdate(){

        $this->vars['order_id'] = post('order_id');

        $this->vars['product_id'] = post('product_id');

        $this->vars['value'] = post('value');

        $partialName = post('partial_name');

        return $this->makePartial($partialName);

    }

    public function onUpdateJson(){

        $targetField = post('target_field');

        $order =  \Codengine\Awardbank\Models\Order::find(post('order_id'));

        $product_id = post('product_id'); 

        $array = $order->delivery_json;

        $array = json_decode($array);

        $product = $array->$product_id;

        $value = post('value');

        $product->$targetField = $value;

        if($targetField == 'product_status'){

            date_default_timezone_set('Australia/Sydney');
            
            if($value == 0){

                $product->product_ordered = null;
                $product->product_backordered = null;
                $product->product_in_warehouse = null;
                $product->product_ready_for_dispatch = null;
                $product->product_shipped = null;
                $product->product_arrived = null;
                $product->product_cancelled = null;

            }else if($value == 1){

                $product->product_ordered = now();
                $product->product_backordered = null;
                $product->product_in_warehouse = null;
                $product->product_ready_for_dispatch = null;
                $product->product_shipped = null;
                $product->product_arrived = null;
                $product->product_cancelled = null;

            } else if ($value == 2){

                $product->product_backordered = now();
                $product->product_in_warehouse = null;
                $product->product_ready_for_dispatch = null;
                $product->product_shipped = null;
                $product->product_arrived = null;
                $product->product_cancelled = null;

            } else if ($value == 3){

                $product->product_in_warehouse = now();
                $product->product_ready_for_dispatch = null;
                $product->product_shipped = null;
                $product->product_arrived = null;
                $product->product_cancelled = null;

            } else if ($value == 4){

                $product->product_ready_for_dispatch = now();
                $product->product_shipped = null;
                $product->product_arrived = null;
                $product->product_cancelled = null;

            } else if ($value == 5){       

                $product->product_shipped = now();
                $product->product_arrived = null;
                $product->product_cancelled = null;

            } else if ($value == 6){       

                $product->product_arrived = now();
                $product->product_cancelled = null;

            } else if ($value == 7){       

                $product->product_cancelled = now();

            }   


        }

        if($targetField == 'invoice_number'){

            $product->product_status = 3;
            $product->product_in_warehouse = now();           

        }

        if($targetField == 'product_con_note'){

            $product->product_status = 4;
            $product->product_ready_for_dispatch = now();           

        }

        if($targetField == 'product_voucher_code'){

            $product->product_voucher = 1;
            $product->product_status = 6;
            $product->product_arrived = now();           

        }
        $array->$product_id = $product;

        $arrayJson = json_encode($array);

        $order->delivery_json = $arrayJson;

        $order->save();

        return ['#orderProductTable' => $this->makePartial('partial_order_product_table', [
                'array' => $array, 
                'order_id' => $order->id,
            ])
        ];

    }
}