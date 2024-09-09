<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreditExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $credits = Credit::all();

        foreach($credits as $credit){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $credit->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}