<?php namespace Codengine\Awardbank\Models;

use Model;
use RainLab\User\Models\User as User;

/**
 * Model
 */
class Result extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array Fillable fields
     */
    protected $guarded = ['created_at','updated_at','deleted_at'];

    public $belongsTo = [
        'resulttype' => 'Codengine\Awardbank\Models\ResultType',
        'region' => 'Codengine\Awardbank\Models\Region',
        'team' => 'Codengine\Awardbank\Models\Team',
        'user' => 'RainLab\User\Models\User'
    ];



    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_results';

    public function beforeCreate(){
        $this->magicAttachments();
    }

    public function beforeSave(){
        $this->magicAttachments();
    }

    public function magicAttachments(){

        $resultType = ResultType::find($this->resulttype_id);

        if($resultType){
            $program = $resultType->resultgroup->program()->with('teams')->first();
            $regionids = [];
            $teamids = [];
            foreach($program->teams as $team){
                $teamids[] = $team->id;
            }
            if($this->attachment_level == 'region'){
                $this->team_id = null;
                $this->user_id = null;
            }elseif($this->attachment_level == 'team'){
                $this->user_id = null;
                $team = Team::find($this->team_id);
                if($team){
                    foreach($team->regions as $region){
                        if(in_array($region->id, $regionids)){
                            $this->region_id = $region->id;
                            break;
                        }
                    }
                }
            }else{
                $user = User::find($this->user_id);
                if($user){
                    foreach($user->teams as $team){
                        if(in_array($team->id, $teamids)){
                            $this->team_id = $team->id;
                            break;
                        }
                    }
                }
            }
        }
    }

    public function getRegionOptions() {
        $programid = $this->resulttype->resultgroup->program->id ?? null;

        if ($programid) {
            $rows = Region::where('program_id', '=', $programid)->get();
        } else {
            $rows = Region::all();
        }

        $regions = [];
        foreach ($rows as $region) {
            $regions[$region->id] = $region->name;
        }

        return $regions;
    }
}
