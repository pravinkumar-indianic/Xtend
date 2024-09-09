<?php namespace Codengine\Awardbank\Models;

use Model;
use Session;
use Auth;
use BackendAuth;
use DB;
use Schema;

/**
 * Model
 */
class Nomination extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['id','award_id','user_id','is_winner','created_user_id','approved_user_id'];

    public $hasMany = [
        'votes' => 'Codengine\Awardbank\Models\Vote'
    ];

    public $belongsTo = [
        'award' => ['Codengine\Awardbank\Models\Award', 'key' => 'award_id'],
        'user' => ['Rainlab\User\Models\User', 'key' => 'nominated_user_id'],
        'approver' => ['Rainlab\User\Models\User', 'key' => 'approved_user_id'],
        'team' => ['Codengine\Awardbank\Models\Team', 'key' => 'team_id'],
        'nominator' => ['Rainlab\User\Models\User', 'key' => 'created_user_id'],
        'program' => ['Codengine\Awardbank\Models\Program', 'key' => 'program_id'],
    ];

    public $attachOne = [
        'nomination_image' => 'System\Models\File',
        'nomination_file' => 'System\Models\File',
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $jsonable = [

        'questions_answers',

    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_nominations';

    public function beforeSave(){
        if($this->user){
            $this->nominee_full_name = $this->user->full_name;
        }
        if($this->team){
            $this->nominee_full_name = $this->team->name;
        }
        if($this->nominator){
            $this->created_full_name = $this->nominator->full_name;
        }
        $this->votescount = $this->votes()->count();

        if($this->managed){
            unset($this->managed);
        }
    }

    public function getIsVotedAttribute()
    {
        $auth = Auth::getUser();
        return in_array($auth->id, ($this->votes->lists('voter_id')));

    }

    public function getTotalVotesAttribute(){

        $votes = $this->votes->count();

        return $votes;
    }

    public function scopeIsApproved($query)
    {
        return $query->where('approved_at','!=', null);
    }

    public function scopeFilterTimeRange($query,$response){

        /**CHECK IF SESSION HAS SAVED FILTERS OR COMING FROM NEW POST REQUEST**/

        $types['created_range'] = null;
        $types['updated_range'] = null;

        if(!empty($this->getCurrentFilters())){
            foreach($this->getCurrentFilters() as $value){
                foreach($value as $key => $value){
                    if(!empty($value)){
                        $newkey = substr($key,6);
                        $types[$newkey] = $value;
                    }
                }
            }

        } else {

            $types[] = [post('scopeName') => $response ];

        }

        if(is_array($types['created_range'])){

            $column = 'created_at';
            $after = $types['created_range'][0];
            $before = $types['created_range'][1];

        }

        if(is_array($types['updated_range'])){

            $column = 'updated_at';
            $after = $types['updated_range'][0];
            $before = $types['updated_range'][1];

        }

        return $query->where($column, '>=', $after)->where($column, '<=', $before);

    }

  public function getCurrentFilters()
    {
        $filters = [];

        foreach (Session::get('widget', []) as $name => $item) {
            if (str_contains($name, 'Filter')) {
                $filter = @unserialize(@base64_decode($item));
                if ($filter) {
                    $filters[] = $filter;
                }
            }
        }

        return $filters;
    }

    public function scopeFilterProcess($query, $response)
    {

        /**SET DEFAULT LEFT TO RIGHT ORDER TO RUN FILTERS IN**/

        $type['programs'] = null;

        /**CHECK IF SESSION HAS SAVED FILTERS OR COMING FROM NEW POST REQUEST**/

        if(!empty($this->getCurrentFilters())){
            foreach($this->getCurrentFilters() as $value){
                foreach($value as $key => $value){
                    if(!empty($value)){
                        $newkey = substr($key,6);
                        $types[$newkey] = $value;
                    }
                }
            }

        } else {

            $types[] = [post('scopeName') => $response ];

        }

        /**RUN THROUGH AVAILABLE KEYS**/

        foreach($types as $key => $type){

            /**COMPLETENESS PROCESS**/

            /**ACCESS FILTER**/

            if(($key == 'programs') && !empty($types[$key])){

                /** CHECK AVAILABLE PERMISSIONS **/

                $ids = [];

                foreach($query->get() as $row){
                    if(array_key_exists($row->program_id, $type)){
                        if(in_array($row->id, $ids) == false){
                            $ids[] = $row->id;
                        }
                    }
                }

                $query->whereIn('id', $ids);

            }
        }
    }
}
