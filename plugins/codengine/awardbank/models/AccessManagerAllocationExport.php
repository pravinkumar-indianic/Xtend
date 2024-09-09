<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccessManagerAllocationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $AccessManagers = AccessManagerAllocation::all();

        foreach($AccessManagers as $accessmanager){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $accessmanager->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}