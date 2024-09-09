<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PrizeExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $prizes = Prize::all();

        foreach($prizes as $prize){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $prize->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}