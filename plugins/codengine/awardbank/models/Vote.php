<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Vote extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    public $jsonable = [

        'questions_answers',
        
    ];
    
    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'id',
        'nomination_id',
        'voter_id'];

    public $belongsTo = [
        'nomination' => 'Codengine\Awardbank\Models\Nomination',
        'voter' => [
            'Rainlab\User\Models\User', 
            'key' => 'voter_id'
        ],
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_votes';
}