<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $comments = Comment::all();

        foreach($comments as $comment){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $comment->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}