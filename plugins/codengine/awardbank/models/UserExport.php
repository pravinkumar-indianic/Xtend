<?php
namespace Codengine\Awardbank\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Rainlab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use Illuminate\Support\Facades\Log;

class UserExport extends \Backend\Models\ExportModel
{


    protected $fillable = [

        'program',

    ];

    public function exportData($columns, $sessionKey = null)
    {
        $relationshipArray = [
            'programs',
            'teams',
            'programs_manager',
            'teams_manager'
        ];

        $program = post('ExportOptions.program');

        $skip = post('ExportOptions.skip');

        $take = post('ExportOptions.take');

        $program = Program::where('id','=', $program)->first();

        $models = User::where('id','>=',1)->skip($skip)->take($take);

        if($program){
            $models = $models->whereHas('programs', function($query) use ($program){
                $query->where('id','=',$program->id);
            });
        }

        $models = $models->with($relationshipArray)->get();

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

    public function getProgramOptions()
    {
        $programs = Program::all(['id','name']);
        $array = [0 => 'All'];
        foreach($programs as $program){
            $array[$program->id] = $program->name;
        }
        return $array;
    }
}
