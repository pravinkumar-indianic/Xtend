<?php namespace Codengine\Awardbank\Models;

use Auth;
use Illuminate\Support\Facades\DB;
use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class SocialPost extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $casts = [
        'tags' => 'array'
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'program_id', 'content', 'pinned', 'media_type' , 'media_url', 'poster_id'
    ];

    public $belongsTo = [
        'program' => 'Rainlab\User\Models\Program',
        'poster' => 'Rainlab\User\Models\User',
    ];

    public $hasMany = [
        'responses' => [
            'Codengine\Awardbank\Models\SocialPostResponse'
        ],
    ];

    /*
     * Validation
     */

    public $rules = [
        'program_id' => 'required',
        // Can be file only
        //'content' => 'required',
        'poster_id' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_social_posts';

    public $attachMany = [
        'attachments' => 'System\Models\File',
    ];

    public function getCommentsAttribute() {
        return $this->responses->where('response_type', '=', 'comment');
    }

    public function getLikesAttribute() {
        return $this->responses->where('response_type', '=', 'like');
    }

    public function getLikePostersListAttribute() {
        $likes = $this->responses->where('response_type', '=', 'like');
        $postersList = [];
        foreach ($likes as $like) {
            $name = empty($like->poster->full_name) ?
                (empty($like->poster->business_name) ? 'Anonymous user' : $like->poster->business_name)
                : $like->poster->full_name;
            /*if ($name == 'Anonymous user') {
                if (!in_array($name, $postersList)) {
                    $postersList[] = $name;
                }
            } else {
                $postersList[] = $name;
            }*/
            if (count($postersList) < 9) {
                $postersList[] = $name;
            } else {
                $postersList[] = 'and ' . (count($likes) - count($postersList)) . ' more...';
                break;
            }
        }

        return implode('<br/>', $postersList);
    }

    public function getLikedByCurrentUserAttribute() {
        $user = Auth::getUser();
        foreach ($this->likes as $like) {
            if ($like->poster_id == $user->id) {
                return true;
            }
        }

        return false;
    }

    public function getRecentCommentPostersAttribute() {
        $user = Auth::getUser();
        $query = "SELECT poster_id FROM codengine_awardbank_social_posts_responses
                WHERE social_post_id = :social_post_id 
                AND response_type = :response_type
                AND poster_id NOT IN (" . implode(",", [$user->id, $this->poster->id]) . ") 
                GROUP BY poster_id
                ORDER BY MAX(created_at) DESC
                LIMIT 3";

        $posters = [];
        $poster_rows = DB::select($query, [
                'social_post_id' => $this->id,
                'response_type' => 'comment'
            ]
        );

        if (!empty($poster_rows)) {
            foreach ($poster_rows as $poster_row) {
                $posters[$poster_row->poster_id] = [];
            }

            if (!empty($posters)) {
                $users = User::whereIn('id', array_keys($posters))->get();
                if (!empty($users)) {
                    foreach ($users as $user) {
                        if (isset($posters[$user->id])) {
                            $posters[$user->id] = $user;
                        }
                    }
                }
            }
        }

        return $posters;
    }
}
