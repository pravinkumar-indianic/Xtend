<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $transactions = Transaction::all();

        foreach($transactions as $transaction){
            $return = [];
            foreach($columns as $column){
                $return[$column] = $transaction->$column;
            }
            $result[] = $return;
        }
        return $result;
    }
}