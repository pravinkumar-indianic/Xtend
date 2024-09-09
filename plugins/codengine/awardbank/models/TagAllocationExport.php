<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagAllocationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $tagAllocations = tagAllocation::all();

        foreach($tagAllocations as $tagAllocation){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $tagAllocation->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}