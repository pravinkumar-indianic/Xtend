<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class ResultGroup extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $guarded = ['created_at','updated_at','deleted_at'];

    public $hasMany = [
        'resulttypes' => ['Codengine\Awardbank\Models\ResultType','key' => 'resultgroup_id'],
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    public $belongsTo = [
        'program' => 'Codengine\Awardbank\Models\Program',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_result_group';

    public $belongsToMany = [

        'targetingtags' => [
            'Codengine\Awardbank\Models\TargetingTag',
            'table'    => 'codengine_awardbank_tt_res',
            'key'      => 'targeting_tag_id',
            'otherKey' => 'result_id'
        ],

    ];
}