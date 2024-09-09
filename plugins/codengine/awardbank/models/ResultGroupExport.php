<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResultGroupExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $results = ResultGroup::all();

        foreach($results as $resultGroup){
            $return = [];
            foreach($columns as $column){
                if($column == 'program'){
                    if($resultGroup->program){
                        $return[$column] = $resultGroup->program->id;          
                    } else {
                        $return[$column] = 'Empty';                   
                    }       
                } else {
                    $return[$column] = $resultGroup->$column;                    
                }
            }
            $result[] = $return;
        }
        return $result;
    }
}