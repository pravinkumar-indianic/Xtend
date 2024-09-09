<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Program;

class TeamExport extends \Backend\Models\ExportModel
{
    protected $fillable = [
        'program',
    ];

    public function exportData($columns, $sessionKey = null)
    {

        $relationshipArray = [
            'users',
            'managers',
        ];

        $program = post('ExportOptions.program');

        $program = Program::where('id','=', $program)->first();

        $models = Team::where('id','>=',1);

        if($program){
            $models = $models->where('program_id','=', $program->id);
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