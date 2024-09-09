<?php namespace Codengine\Awardbank\Models;

use Model;
use Auth;
use Codengine\Awardbank\Models\Permission;

/**
 * Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    // public $hasMany = [
    //     'tagallocation' => 'Codengine\Awardbank\Models\TagAllocation',
    // ];

    public $morphedByMany = [
        'post' => [
            'Codengine\Awardbank\Models\Post', 
            'table' => 'codengine_awardbank_tag_allocation', 
            'name'=>'entity',
        ],
        'product' => [
            'Codengine\Awardbank\Models\Product', 
            'table' => 'codengine_awardbank_tag_allocation', 
            'name'=>'entity'
        ]

    ];   

    public $morphToMany = [ 
        'viewability' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'isViewable'
        ] 
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_tags';

    public function beforeSave(){
        $this->slugAttributes();
    }

    public function afterSave(){
        /**
        $user = Auth::getUser();
        $program = $user->teams()->first()->regions()->first()->program->id;

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->tags()->attach($this->id); 
        $permission->programs()->attach($program);  
        **/
    } 

    public function scopefindByName($query,$relation)
    {
        return $query->where('name', $relation);
    }
}