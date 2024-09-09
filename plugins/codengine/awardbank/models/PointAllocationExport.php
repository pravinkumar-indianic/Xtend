<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PointAllocationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $pointAllocations = PointAllocation::all();

        foreach($pointAllocations as $pointAllocation){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $pointAllocation->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}