<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $permissions = Permission::all();

        foreach($permissions as $permission){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $permission->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}