<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResultTypeExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $resultTypes = ResultType::all();

        foreach($resultTypes as $resultType){
            $return = [];
            foreach($columns as $column){
                if($column == 'resultgroup'){
                    if($resultType->resultgroup){
                        $return[$column] = $resultType->resultgroup->id;          
                    } else {
                        $return[$column] = 'Empty';                   
                    }       
                } else {
                    $return[$column] = $resultType->$column;                    
                }
            }
            $result[] = $return;
        }
        return $result;
    }
}