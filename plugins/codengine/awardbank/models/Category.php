<?php namespace Codengine\Awardbank\Models;

use Model;
use Session;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\NestedTree;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    /**
     * @var array Fillable fields
     */
    
    protected $guarded = [];

    /**
    public $hasMany = [
        'categoryallocation' => 'Codengine\Awardbank\Models\CategoryAllocation',
    ];
    **/

    public $belongsTo = [
        'parent' => ['Codengine\Awardbank\Models\Category', 'key' => 'parent_id', 'otherKey' => 'id']
    ];

    public $belongsToMany = [
        'program_exclusions' => ['Codengine\Awardbank\Models\Program', 'table' => 'codengine_awardbank_p_c_ex'],             
    ];

    public $morphedByMany = [
        'post' => [
            'Codengine\Awardbank\Models\Post', 
            'table' => 'codengine_awardbank_category_allocation', 
            'name'=>'entity'
        ],
        'product' => [
            'Codengine\Awardbank\Models\Product', 
            'table' => 'codengine_awardbank_category_allocation', 
            'name'=>'entity'
        ],
        
    ];

    public $morphToMany = [
        'viewability' => [
            'Codengine\Awardbank\Models\Permission', 
            'table' => 'codengine_awardbank_permission_entity_allocation', 
            'name'=>'permissionentityallocatable', 
            'scope' => 'IsViewable'
        ],
    ];

    /*
     * Validation
     */

    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */

    public $table = 'codengine_awardbank_categories';

    /** 

        RELATIONSHIPS

    **/

    public $attachOne = [
        'feature_image' => 'System\Models\File',
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];    


    /**
        SCOPES
    **/

    /**ENSURE CATEGORY NOT DELETED**/

    public function scopeNotDeleted($query){

        return $query->where('deleted_at', null);
        
    }

    public function scopeisProduct($query){

        return $query->where('type', 'product');
        
    }

    public function scopeisPost($query)
    {

        return $query->where('type', 'post');
        
    }

    public function getPostFilter()
    {
        $results = Category::isPost()->get();
        $array;
        foreach ($results as $result) {
            $array[$result->id] = $result->name;
        }
        return $array;
    }

    public function scopeisProgram($query){

        return $query->where('type', 'program');
        
    }

    public function getMyViewAttribute() 
    { 
        $result = '';
        foreach($this->viewability as $permission){
            $result .= $permission->getAccessNamesAttribute();
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    public function scopeFilterProcess($query, $response)
    {

        /**SET DEFAULT LEFT TO RIGHT ORDER TO RUN FILTERS IN**/

        $types['categorytype'] = null;
        $type['organizations'] = null;
        $type['programs'] = null;
        $type['regions'] = null;
        $type['teams'] = null;    
        $type['users'] = null;

        /**CHECK IF SESSION HAS SAVED FILTERS OR COMING FROM NEW POST REQUEST**/

        if(!empty($this->getCurrentFilters())){
            foreach($this->getCurrentFilters() as $value){
                foreach($value as $key => $value){
                    if(!empty($value)){
                        $newkey = substr($key,6);
                        $types[$newkey] = $value;    
                    }
                }    
            }

        } else {

            $types[] = [post('scopeName') => $response ];

        }

        /**RUN THROUGH AVAILABLE KEYS**/

        foreach($types as $key => $type){

            /**CATEGORY TYPE PROCESS**/

            if($key == 'categorytype' && !empty($types[$key])){
                $ids = [];
                foreach($query->get() as $row){
                    if(array_key_exists($row->type, $type)){
                        if(in_array($row->id, $ids) == false){
                            $ids[] = $row->id;
                        }                        
                    }
                }
                $query->whereIn('id', $ids);            
            }
       
            /**ACCESS FILTER**/

            if(($key == 'organizations' || $key == 'programs' || $key == 'regions' || $key =='teams' || $key == 'users') && !empty($types[$key])){

                /** CHECK AVAILABLE PERMISSIONS **/

                if(!empty($types['permissions'])){
                    $availablePermissions = $types['permissions'];
                } else {
                    $availablePermissions['viewability'] = 'viewability';                 
                }

                $ids = [];

                foreach($query->get() as $row){
                    foreach($availablePermissions as $permissionKey => $permissionValue){
                        if($row->$permissionKey->count() >= 1){    
                            foreach($row->$permissionKey as $permission){
                                if($permission->$key){
                                    if($permission->$key->count() >= 1){               
                                        foreach($permission->$key as $result){
                                            if(array_key_exists($result->id, $type)){
                                                if(in_array($row->id, $ids) == false){
                                                    $ids[] = $row->id;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }  

                $query->whereIn('id', $ids);   
            }
        }  
    } 

    public function getCurrentFilters()
    {
        $filters = [];
        
        foreach (Session::get('widget', []) as $name => $item) {
            if (str_contains($name, 'Filter')) {
                $filter = @unserialize(@base64_decode($item));
                if ($filter) {
                    $filters[] = $filter;
                }
            }
        }

        return $filters;
    }   

    public function beforeSave(){
        $this->name = str_replace(array('.', ','), '' , $this->name);
        $this->slugAttributes();
    }   

    public function afterSave(){


    }

    public function filterFields($fields, $context = null)
    {
        $type = $context;
        if($type == 'product'){
            $fields->type->disabled = true;
            $fields->type->value = 'product';
        }
        if($type == 'post'){
            $fields->type->disabled = true;
            $fields->type->value = 'post';
        }        
    }

    public function getNumberActiveProductAttribute(){
        return count($this->product()->where('active', 1)->get());
    }    

    public function runCount(){

        $this->product_count = $this->product->count();
        $this->save();

    } 
    
}