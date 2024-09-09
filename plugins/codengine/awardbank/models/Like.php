<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Like extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id','user_id'];

    public $hasMany = [
        'likeallocation' => 'Codengine\Awardbank\Models\LikeAllocation',
    ];

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];

    public $morphedByMany = [
        'post' => ['Codengine\Awardbank\Models\Post', 'table' => 'codengine_awardbank_p_like', 'name'=>'likeable'],
        'program' => ['Codengine\Awardbank\Models\Program', 'table' => 'codengine_awardbank_p_like', 'name'=>'likeable']
    ];

    /*
     * Validation
     */

    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_likes';

    public function scopeLikeBy($query, $userid)
    {
        return $query->where('user_id', $userid);
    }

    public function getMyUserAttribute() 
    { 
        $result = trim($this->user->full_name);
        return $result;
    }     
}