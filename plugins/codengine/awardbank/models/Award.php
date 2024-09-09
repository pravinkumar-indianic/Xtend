<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Award extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    protected $guarded = [

    ];

    protected $jsonable = [
        'nomination_questions_json',
        'votes_question_json',
    ];

    public $hasMany = [
        'prizes' => 'Codengine\Awardbank\Models\Prize',
        'nominations' => [
            'Codengine\Awardbank\Models\Nomination',
            'key' => 'award_id', 
            'otherKey' => 'id'
        ],
        'votes' => [
            'Codengine\Awardbank\Models\Vote',
            'key'        => 'award_id',
        ],
    ];


    public $belongsTo = [
        'program' => [
            'Codengine\Awardbank\Models\Program', 
            'key' => 'program_id', 
            'otherKey' => 'id',
        ],
        'alias' => 'Rainlab\User\Models\User',
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File',
        'trophy_image' => 'System\Models\File',
    ];

    public $belongsToMany = [
        'paired_award' => [
            'Codengine\Awardbank\Models\Award',
            'table'    => 'codengine_awardbank_a_a_paired',
            'key'      => 'award_id1',
            'otherKey' => 'award_id2',
        ],
        'managers' => [
            'RainLab\User\Models\User',
            'table'    => 'codengine_awardbank_aw_ma',
            'key'      => 'award_id',
            'otherKey' => 'manager_id'
        ],
        'viewingteams' => [
            'Codengine\Awardbank\Models\Team',
            'table'    => 'codengine_awardbank_aw_vt',
            'key'      => 'award_id',
            'otherKey' => 'team_id'
        ], 
        'nominatableusers' => [
            'RainLab\User\Models\User',
            'table'    => 'codengine_awardbank_aw_nu',
            'key'      => 'award_id',
            'otherKey' => 'user_id'
        ], 
        'nominatableteams' => [
            'Codengine\Awardbank\Models\Team',
            'table'    => 'codengine_awardbank_aw_na',
            'key'      => 'award_id',
            'otherKey' => 'team_id'
        ], 
        'nominationteams' => [
            'Codengine\Awardbank\Models\Team',
            'table'    => 'codengine_awardbank_aw_nt',
            'key'      => 'award_id',
            'otherKey' => 'team_id'
        ], 
        'votableteams' => [
            'Codengine\Awardbank\Models\Team',
            'table'    => 'codengine_awardbank_aw_vota',
            'key'      => 'award_id',
            'otherKey' => 'team_id'
        ], 
        'votingteams' => [
            'Codengine\Awardbank\Models\Team',
            'table'    => 'codengine_awardbank_aw_vot',
            'key'      => 'award_id',
            'otherKey' => 'team_id'
        ], 
        'nominationsmanagers' => [
            'RainLab\User\Models\User',
            'table'    => 'codengine_awardbank_aw_nm',
            'key'      => 'award_id',
            'otherKey' => 'user_id'
        ], 
        'winnersmanagers' => [
            'RainLab\User\Models\User',
            'table'    => 'codengine_awardbank_aw_wm',
            'key'      => 'award_id',
            'otherKey' => 'user_id'
        ], 
    ];
    
    public $morphToMany = [
        'owners' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isOwner'],
        'alias' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isAlias'],
        'viewability' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isViewable'
        ],
        'nomination_viewability' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isNomination'],
        'nomination_receive' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isNominationReceive'],
        'vote_viewability' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isVoteViewable'],
        'winnernominationsmngr' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isWinnerNominationsManager'],
        'nominationsmngr' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isNominationsManager'],
        'readonly' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isReadOnly'
        ]
    ];

    /*
     * Validation
     */

    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_awards';

    public function beforeSave(){
        $this->slugAttributes();
    } 

    public function scopeRunning($query){
        $dt = now();
        $query->where(function ($query) use ($dt) {
            $query->where('award_open_at', '<=', $dt);
            $query->where('award_close_at', '>', $dt);
        });
    }

    public function scopeNominationRunning($query){
        $dt = now();
        $query->where(function ($query) use ($dt) {
            $query->where('nominations_open_at', '<=', $dt);
            $query->where('nominations_closed_at', '>', $dt);
        });
    }

    public function scopeVoteRunning($query){
        $dt = now();
        $query->where(function ($query) use ($dt) {
            $query->where('votes_open_at', '<=', $dt);
            $query->where('votes_close_at', '>', $dt);
        });
    }

    public function getIsVoteEndedAttribute(){
        $dt = now();
        if ($this->votes_close_at <= $dt){
            return true;
        }
        return false;
    }

    public function getMyViewAttribute() 
    { 
        $result = '';
        foreach($this->viewability as $permission){
            $result .= $permission->getAccessNamesAttribute();
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    public function getOwnerListAttribute() 
    { 
        $result = '';
        foreach($this->owners as $permission){
            $result = $permission->getPosterAttribute();
        }
        return $result;
    }

    public function getMyOwnerAttribute() 
    { 
        $result = '';
        foreach($this->owners as $permission){
            $result .= $permission->getAccessNamesAttribute();
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    public function getMyNomineesAttribute(){
        $result = '';
        foreach($this->vote_viewability as $permission){
            $result = $permission->getPosterAttribute();
        }
        return $result;
    }

    public function getTotalVotesAttribute(){
        $votes = 0;
        foreach ($this->nominations as $nom) {
            $votes += $nom->votes->count();
        }
        return $votes;
    }

    public function getWinnerCandidateAttribute(){
        $winners = [];

        foreach ($this->nominations as $nom) {
            if (!in_array($nom->nominated_user_id, $this->prizeWinner)){
                array_push($winners, $nom);
            }
        }
        return $winners;
    }

    public function getPrizeWinnerAttribute(){
        $winner = [];
        foreach ($this->prizes as $prize) {
            if ($prize->winner_id > 0)
                array_push($winner, $prize->winner_id);
        }
        return $winner;
    }

    public function getTotalPointsAttribute(){
        $points = 0;
        foreach ($this->prizes as $prize) {
            $points = $prize->getTotalPointsAttribute();
        }
        return $points;
    }

    public function firstPrizeValue(){
        if($this->prizes()->count() >= 1){
            $prize = $this->prizes()->orderBy('prize_value','desc')->first();
            $result = $prize->prize_value;
        } else {
            $result = 0;
        }
        return $result;

    }

    public function scopeFilterPrograms($query, $response)
    {
        $query->whereIn('program_id',$response);
    }
}