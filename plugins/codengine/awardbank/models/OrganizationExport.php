<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrganizationExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $organizations = Program::all();

        foreach($organizations as $organization){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $organization->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}