<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikeExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $likes = Like::all();

        foreach($likes as $like){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $like->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}