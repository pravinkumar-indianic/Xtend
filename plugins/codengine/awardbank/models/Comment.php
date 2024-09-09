<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Comment extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id','comment','user_id'];

    public $hasMany = [
        'commentallocation' => 'Codengine\Awardbank\Models\CommentAllocation',
    ];

    public $belongsTo = [
        'user' => 'Rainlab\User\Models\User'
    ];

    public $morphedByMany = [
        'post' => ['Codengine\Awardbank\Models\Post', 'table' => 'codengine_awardbank_p_comment', 'name'=>'commantable'],
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_comments';

    public function scopeLatestComment($query, $lastcomment_id){
        return $query->where('comment_id', '>', $lastcomment_id);
    }

    public function getMyUserAttribute() 
    { 
        $result = trim($this->user->full_name);
        return $result;
    }  
}