<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class ScorecardResult extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'scorecard_criteria_id', 'month', 'pharmacy', 'points', 'data'
    ];

    public $belongsTo = [
        'scorecard_criteria' => 'Codengine\Awardbank\Models\ScorecardCriteria',
    ];

    public $hasOne = [
        'pharmacy' => [
            'RainLab\User\Models\User',
            'key' => 'crm',
            'otherKey' => 'crm'
        ],
    ];

    /*
     * Validation
     */

    public $rules = [
        'scorecard_criteria_id' => 'required',
        'month' => 'required|date',
        'crm' => 'required',
        'points' => 'required',
        'data' => 'required'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_scorecard_results';
}
