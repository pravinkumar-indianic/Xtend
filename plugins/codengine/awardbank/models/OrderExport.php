<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $orders = Order::all();

        foreach($orders as $order){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $order->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}