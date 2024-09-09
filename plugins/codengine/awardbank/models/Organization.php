<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Organization extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];


    protected $fillable = ['id','name','description','primary_color','secondary_color'];    

    /*
     * Validation
     */

    public $rules = [
        'name'    => 'required|string',
        'description'   => 'nullable|string',
        'primary_color' => 'required|string',
        'secondary_color' => 'required|string',

    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_organizations';

    public $hasOne = [
        'billingcontact' => 'Codengine\Awardbank\Models\BillingContact'
    ];

    public $hasMany = [
        'programs' => ['Codengine\Awardbank\Models\Program']
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File',
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];  

    public $morphToMany = [
        'accessmanager' => ['Rainlab\User\Models\User', 'table' => 'codengine_awardbank_access_manager_allocation', 'name'=>'managable'],    
        'permissions' => ['Codengine\Awardbank\Models\Permission', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],                                 
    ];

    public $belongsTo = [     
        'billingcontact' => 'Codengine\Awardbank\Models\BillingContact',  
    ];

    public function scopeIsActive($query)
    {
        return $query->where('deleted_at', null);   
    }

    public function beforeSave(){
        $this->slugAttributes();
    } 
         
}