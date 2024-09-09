<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddressExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $addresses = Address::all();

        foreach($addresses as $address){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $address->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}