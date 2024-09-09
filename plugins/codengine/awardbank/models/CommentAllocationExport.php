<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentAllocationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $commentAllocations = CommentAllocation::all();

        foreach($commentAllocations as $commentAllocation){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $commentAllocation->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}