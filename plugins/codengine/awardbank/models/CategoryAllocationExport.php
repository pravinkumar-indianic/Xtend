<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryAllocationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $categoryAllocations = PermissionEntityAllocation::all();

        foreach($categoryAllocations as $categoryAllocation){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $categoryAllocation->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}