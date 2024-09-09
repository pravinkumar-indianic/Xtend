<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegionExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $regions = Region::all();

        foreach($regions as $region){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $region->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}