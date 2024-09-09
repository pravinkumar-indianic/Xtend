<?php
namespace Codengine\Awardbank\Models;
use Codengine\Awardbank\Models\Program;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Codengine\Awardbank\Models\Team;

class ProgramExport extends \Backend\Models\ExportModel
{

    public function exportData($columns, $sessionKey = null)
    {

        $relationshipArray = [
            'teams',
            'managers',
            'billing_invoices',
            'pointsledgers',
            'product_exclusions',
            'category_exclusions',
            'resultgroups',
        ];

        $models = Program::query()->where('id','>=',1)
            ->selectSub(function ($query) {
                $query->selectRaw('GROUP_CONCAT(CONCAT_WS(" ", users.name,users.surname))')
                    ->from('users')
                    ->join('codengine_awardbank_u_p as up', 'up.user_id', '=', 'users.id')
                    ->whereColumn('up.program_id', 'codengine_awardbank_programs.id')->groupBy('up.program_id');
            }, 'users')->with($relationshipArray)->get();

        $result = [];

        foreach($models as $model){
            $return = [];
            foreach($columns as $column){
                if(in_array($column,$relationshipArray)){
                    $return[$column] = $this->relationShipColumnProcess($model->$column);
                } else {
                    $return[$column] = $model->$column;
                }
            }
            $result[] = $return;
        }
        return $result;
    }

    public function relationShipColumnProcess($relationship)
    {
        $string = '';
        $count = $relationship->count();
        $i = 1;
        foreach($relationship as $key => $value){
            if($i == $count){
                $string .= $value->id;
            } else {
                $string .= $value->id.';';
            }
            $i++;
        }
        return $string;
    }
}
