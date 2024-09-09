<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class ResultType extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $guarded = ['created_at','updated_at','deleted_at'];

    public $hasMany = [
        'results' => ['Codengine\Awardbank\Models\Result','key' => 'resulttype_id', 'otherKey' => 'id']
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    public $belongsTo = [
        'resultgroup' => 'Codengine\Awardbank\Models\ResultGroup',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_result_type';
}