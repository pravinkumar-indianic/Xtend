<?php namespace Codengine\Awardbank\Models;

use Model;
use Auth;
use Session;

/**
 * Model
 */

class Post extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'title'];

    protected $fillable = [
        'id',
        'title',
        'content',
        'viewed_at',
        'post_type',
        'pinned',
        'video_url',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $jsonable = [
        'category_json'
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File',
        'files' => 'System\Models\File'
    ];

    public $morphToMany = [
        'categories' => [
            'Codengine\Awardbank\Models\Category', 
            'table' => 'codengine_awardbank_category_allocation', 
            'name'=>'entity',
            'scope' => 'isPost',
        ],
        'tags' => [
            'Codengine\Awardbank\Models\Tag', 
            'table' => 'codengine_awardbank_tag_allocation', 
            'name'=>'entity',       
        ],
   
        'owners' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isOwner'
        ],  
        'viewability' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isViewable'
        ],
        /**
        'alias' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isAlias'
        ],  
        **/
           
    ];

    public $belongsTo = [
        'poster' => 'Rainlab\User\Models\User',
        'alias' => 'Rainlab\User\Models\User',
    ];

    public $belongsToMany = [
        'managers' => [
            'RainLab\User\Models\User',
            'table'    => 'codengine_awardbank_po_ma',
            'key'      => 'post_id',
            'otherKey' => 'user_id'
        ],  

        'teams' => [
            'Codengine\Awardbank\Models\Team',
            'table'    => 'codengine_awardbank_po_te',
            'key'      => 'post_id',
            'otherKey' => 'team_id'
        ], 
        'programs' => [
            'Codengine\Awardbank\Models\Program',
            'table'    => 'codengine_awardbank_po_pr',
            'key'      => 'post_id',
            'otherKey' => 'program_id'
        ],  
        'targetingtags' => [
            'Codengine\Awardbank\Models\TargetingTag',
            'table'    => 'codengine_awardbank_tt_post',
            'key'      => 'targeting_tag_id',
            'otherKey' => 'post_id'
        ],
        'comments' => [
            'Codengine\Awardbank\Models\Comment', 
            'table'    => 'codengine_awardbank_po_co',
            'key'      => 'post_id',
            'otherKey' => 'comment_id'
        ],
        'likes' => ['Codengine\Awardbank\Models\Comment', 
            'table'    => 'codengine_awardbank_po_li',
            'key'      => 'post_id',
            'otherKey' => 'like_id'
        ],
    ];

    /*
     * Validation
     */

    public $rules = [
        'title' => 'required', 
        'content' => 'required',
        'post_type' => 'required',               
    ];

    /**
     * @var string The database table used by the model.
     */

    public $table = 'codengine_awardbank_posts';

    /**
        MODEL EVENT PROCESSES
    **/

    public function beforeSave(){
        $this->slugAttributes();
    } 


    /** 
        RELATION SCOPES 
    **/

    public function scopeIsPost($query)
    {
        return $query->where('post_type', 'post');
    }

    public function scopeIsProgram($query)
    {
        return $query->where('post_type', 'program-tool');
    }

    /** 
        FILTER SCOPES
    **/

    public function scopeFilterWithDeleted($query, $response){
        if($response == true){
            $query->withTrashed();
        }
    }

    public function scopeFilterHasPrograms($query,$response){
        $query->whereHas('programs',function($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasPoster($query,$response){
        $query->whereHas('poster',function($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasManagers($query,$response){
        $query->whereHas('managers',function($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasTargetingTags($query,$response){
        $query->whereHas('targetingtags',function($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasCategorys($query,$response){
        $query->whereHas('categories',function($query) use ($response) {
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasTags($query,$response){
        $query->whereHas('tags',function($query) use ($response){
            $query->whereIn('id', $response);
        });
    }                
}