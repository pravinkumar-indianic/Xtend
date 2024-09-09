<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KittyExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $kitties = Kitty::all();

        foreach($kitties as $kitty){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $kitty->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}