<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $categories = Category::all();

        foreach($categories as $category){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $category->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}