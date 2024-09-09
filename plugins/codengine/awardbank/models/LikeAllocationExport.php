<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikeAllocationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $likeAllocations = LikeAllocation::all();

        foreach($likeAllocations as $likeAllocation){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $likeAllocation->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}