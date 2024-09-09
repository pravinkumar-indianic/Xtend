<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class LeaderboardResult extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'month', 'section' ,'crm', 'points'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program',
    ];

    /*
     * Validation
     */

    public $rules = [
        'program_id' => 'required',
        'month' => 'required|date',
        'section' => 'required',
        'crm' => 'required',
        'points' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_results_leaderboard';
}
