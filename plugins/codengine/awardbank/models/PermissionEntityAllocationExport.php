<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionEntityAllocationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $permissionEntityAllocations = PermissionEntityAllocation::all();

        foreach($permissionEntityAllocations as $permissionEntityAllocation){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $permissionEntityAllocation->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}