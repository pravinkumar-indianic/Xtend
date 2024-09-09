<?php

namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class GoogleAnalytics extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_google_analytics';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    /**
     * Mass Assignment
     * @var [type]
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
