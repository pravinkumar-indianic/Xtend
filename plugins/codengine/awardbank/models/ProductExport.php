<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductExport extends \Backend\Models\ExportModel
{

    public function exportData($columns, $sessionKey = null)
    {
        $products = Product::all();

        foreach($products as $product){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $product->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}