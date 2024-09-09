<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $tags = Tag::all();

        foreach($tags as $tag){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $tag->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}