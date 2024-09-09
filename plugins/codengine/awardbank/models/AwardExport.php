<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AwardExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $awards = Award::all();

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