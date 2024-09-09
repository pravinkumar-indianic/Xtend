<?php namespace Codengine\Awardbank\Models;

use Model;
use RainLab\User\Facades\Auth;

/**
 * Model
 */
class SocialPostResponse extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'likes' => 'array'
    ];

    protected $fillable = [
        'social_post_id', 'response_type', 'response_content'
    ];

    public $belongsTo = [
        'social_post' => 'Codengine\Awardbank\Models\SocialPost',
        'poster' => 'Rainlab\User\Models\User',
    ];

    /*
     * Validation
     */

    public $rules = [
        'social_post_id' => 'required',
        //Can be file only
        //'response_content' => 'required',
        'poster_id' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_social_posts_responses';

    public $attachOne = [
        'attachment' => 'System\Models\file',
    ];

    public $attachMany = [

    ];

    public function getLikesCountAttribute() {
        return count($this->likes ?? []);
    }

    public function getlikedByCurrentUserAttribute() {
        $user = Auth::getUser();
        return in_array($user->id, $this->likes ?? []);
    }
}
