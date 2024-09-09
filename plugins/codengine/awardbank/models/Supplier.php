<?php namespace Codengine\Awardbank\Models;

use Model;
use Session;

/**
 * Model
 */
class Supplier extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    /**
     * @var array Fillable fields
     */
    protected $guarded = [
        'id',
    ];    

    /*
     * Validation
     */
    public $rules = [

    ];

    public $jsonable = [

        'territory',

    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_suppliers';

    public $belongsTo = [
        'address' => 'Codengine\Awardbank\Models\Address',
        'shipping_rate' => 'Codengine\Awardbank\Models\Address',
    ];    

    public $hasMany = [
        
        'products' => 'Codengine\Awardbank\Models\Product',

    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $morphToMany = [
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
    ];

    /**
        MODEL EVENT PROCESSES
    **/

    public function beforeSave(){

        //$this->name = str_replace(array('.', ','), '' , $this->name);
        $this->slugAttributes();
    }  

    public function afterSave(){

        /**

        $products = $this->products()->get();

        foreach($products as $product){

            $product->save();

        }

        **/
        
    }

   /** 
        FILTER SCOPES
    **/

    public function scopeFilterWithDeleted($query, $response){
        if($response == true){
            $query->withTrashed();
        }
    }

    public function scopeFilterWithoutImage($query){
        $query->has('feature_image', '<=', 0)->get();      
    }

    public function scopeFilterWithoutProducts($query, $response){
        if($response == true){
            $query->has('products', '=', 0);
        }
    }

    /**
        Permission Relationship Attributes / Printability
    **/

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

    /**

    public function getCompletenessAttribute()
    {

        $score = 0;
        $totalpoints = 0;

        if($this->name == null || $this->name == ''){
            $score = $score + 1;
        }
        $totalpoints = $totalpoints + 1;

        if($this->description == null || $this->description == ''){
            $score = $score + 1;
        }
        $totalpoints = $totalpoints + 1;

        if($this->mark_up_integer == null || $this->mark_up_integer == ''){
            $score = $score + 1;
        }
        $totalpoints = $totalpoints + 1;

        if($this->mark_up_type == null || $this->mark_up_type == ''){
            $score = $score + 1;
        }
        $totalpoints = $totalpoints + 1;

        if($this->primary_color == null || $this->primary_color == ''){
            $score = $score + 1;
        }
        $totalpoints = $totalpoints + 1;

        if(!$this->feature_image){
            $score = $score + 2;
        }
        $totalpoints = $totalpoints + 2;

        if(!$this->address){
            $score = $score + 4;
        }
        $totalpoints = $totalpoints + 4;

        if(count($this->products) == 0){
            $score = $score + 4;
        }
        $totalpoints = $totalpoints + 4;

        if(count($this->owners) == 0){
            $score = $score + 4;
        }
        $totalpoints = $totalpoints + 4;        

        if(count($this->viewability) == 0){
            $score = $score + 4;
        }
        $totalpoints = $totalpoints + 4;           

        $score = ($score/$totalpoints)*100;

        $score = number_format($score, 2, '.', '');

        $score = $score. '%';

        return $score;

    }

    **/
}