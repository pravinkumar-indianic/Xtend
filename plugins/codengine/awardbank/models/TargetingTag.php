<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class TargetingTag extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name'];

    /*
     * Validation
     */

    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_targeting_tags';

    public $belongsToMany = [

        'users' => [
            'Rainlab\User\Models\User',
            'table'    => 'codengine_awardbank_tt_user',
            'key'      => 'user_id',
            'otherKey' => 'targeting_tag_id'
        ],

        'posts' => [
            'Codengine\Awardbank\Models\Post',
            'table'    => 'codengine_awardbank_tt_post',
            'key'      => 'post_id',
            'otherKey' => 'targeting_tag_id'
        ],

    ];
}