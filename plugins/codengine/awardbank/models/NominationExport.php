<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NominationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $nominations = Nomination::all();

        foreach($nominations as $nomination){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $nomination->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}