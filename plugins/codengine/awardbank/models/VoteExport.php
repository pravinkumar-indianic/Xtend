<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VoteExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $votes = Vote::all();

        foreach($votes as $vote){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $vote->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}