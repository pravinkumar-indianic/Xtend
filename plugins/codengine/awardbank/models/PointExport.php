<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PointExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $points = Point::all();

        foreach($points as $point){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $point->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}