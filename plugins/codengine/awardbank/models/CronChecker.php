<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class CronChecker extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_cronchecker';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
