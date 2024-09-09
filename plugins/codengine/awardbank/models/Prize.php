<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Prize extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'id',
        'award_id',
        'winner_id',
        'name',
        'order'
    ];

    public $belongsTo = [
        'award' => ['Codengine\Awardbank\Models\Award'],
    ];

    public $belongsToMany = [
        'userwinners' => [
            'Rainlab\User\Models\User',
            'table'    => 'codengine_awardbank_pr_uw',
            'key'      => 'prize_id',
            'otherKey' => 'user_id',
        ],
        'teamwinners' => [
            'Codengine\Awardbank\Models\Team',
            'table'    => 'codengine_awardbank_pr_tw',
            'key'      => 'prize_id',
            'otherKey' => 'team_id'
        ],
    ];

    /*
     * Validation
     */

    public $rules = [
        'name' => 'required', 
        'order' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_prizes';

    public function beforeSave()
    {
        if($this->managed){
            unset($this->managed);
        }
        if($this->points_name){
            unset($this->points_name);
        }
        if($this->color){
            unset($this->color);
        }   
    }
}