<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderLineItemExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $awards = OrderLineItem::all();

        foreach($awards as $award){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $award->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}