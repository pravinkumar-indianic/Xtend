<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderPointExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $orderPoints = OrderPoint::all();

        foreach($orderPoints as $orderPoint){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $orderPoint->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}