<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierExport extends \Backend\Models\ExportModel
{

    public $jsonable = [

        'territory',

    ];
    
    public function exportData($columns, $sessionKey = null)
    {
        $suppliers = Supplier::all();

        foreach($suppliers as $supplier){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $supplier->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}