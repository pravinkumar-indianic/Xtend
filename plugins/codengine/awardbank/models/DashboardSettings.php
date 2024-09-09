<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * DashboardSettings Model
 */
class DashboardSettings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_program_dashboard_settings';

    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        'template' => 'required',
        'announcement' => 'required',
    ];


    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['announcement'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}
