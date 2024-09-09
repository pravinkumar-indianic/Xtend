<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class ScorecardCriteria extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'target' => 'array',
    ];

    protected $fillable = [
        'scorecard_section_id', 'key', 'name', 'description', 'value'
    ];

    public $belongsTo = [
        'scorecard_section' => 'Codengine\Awardbank\Models\ScorecardSection',
    ];

    public $hasMany = [
        'results' => [
            'Codengine\Awardbank\Models\ScorecardResult'
        ],
    ];

    /*
     * Validation
     */

    public $rules = [
        'scorecard_section_id' => 'required',
        'key' => 'required|unique:codengine_awardbank_scorecard_criteria',
        'label' => 'required',
        'tooltip' => 'required',
        'target' => 'required'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_scorecard_criteria';
}
