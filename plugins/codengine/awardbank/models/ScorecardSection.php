<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class ScorecardSection extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'program_id', 'name', 'type'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program',
    ];

    public $hasMany = [
        'criteria' => [
            'Codengine\Awardbank\Models\ScorecardCriteria'
        ],
    ];

    /*
     * Validation
     */

    public $rules = [
        'program_id' => 'required',
        'step' => 'required',
        'name' => 'required',
        'type' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_scorecard_sections';

    public $attachOne = [
        'logo' => 'System\Models\file',
    ];
}
