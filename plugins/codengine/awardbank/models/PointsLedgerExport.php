<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PointsLedgerExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $results = PointsLedger::all();

        foreach($results as $result){
            $return = [];
            foreach($columns as $column){
                if($column == 'user'){
                    if($result->user){
                        $return[$column] = $result->user->email;          
                    } else {
                        $return[$column] = 'Empty';                   
                    }                
                }
                else {
                    $return[$column] = $result->$column;
                }
            }
            $response[] = $return;
        }
        return $response;
    }
}