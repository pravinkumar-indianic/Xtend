<?php namespace Codengine\Awardbank\Models;

use Auth;
use Model;

/**
 * Model
 */
class ResultsWidgetSettings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'program_id', 'key', 'value'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program',
    ];

    /*
     * Validation
     */

    public $rules = [
        'program_id' => 'required',
        'key' => 'required',
        'value' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_results_widget_settings';
}
