<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ThankyouExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $results = Thankyou::all();
        foreach($results as $result){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $result->$column;
            }
            $response[] = $return;
        }
        return $response;
    }
}