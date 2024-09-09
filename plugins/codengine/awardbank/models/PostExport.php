<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $posts = Post::all();

        foreach($posts as $post){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $post->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}