<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Region extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    protected $fillable = [
        'id',
        'name',
        'description',
        'primary_color',
        'secondary_color',
        'can_buy_points',
        'points_limit',
        'external_reference',
        'program_id',
        'slug'
    ];      
    /*
     * Validation
     */

    public $rules = [

        'name'    => 'required|string',
        'description'   => 'nullable|string',
        'primary_color' => 'required|string',
        'secondary_color' => 'required|string',
        'can_buy_points' => 'nullable|integer',
        'points_limit' => 'nullable|integer',
        'external_reference' => 'nullable|string',

    ];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_regions';

    public function beforeSave(){
        $this->slugAttributes();
    } 

    public $belongsTo = [
        'program' => ['Codengine\Awardbank\Models\Program'],
        'billingcontact' => ['Codengine\Awardbank\Models\BillingContact', 'key' => 'billingcontact_id', 'otherKey' => 'id'],
    ];

    public $belongsToMany = [
        'teams' => ['Codengine\Awardbank\Models\Team','table' => 'codengine_awardbank_t_r']
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $morphToMany = [
        'accessmanager' => ['Rainlab\User\Models\User', 'table' => 'codengine_awardbank_access_manager_allocation', 'name'=>'managable'],    
        'permissions' => ['Codengine\Awardbank\Models\Permission', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],                            
    ];

    public $hasMany = [
        'results' => ['Codengine\Awardbank\Models\Result'], 
    ];

    public function scopeIsActive($query)
    {
        return $query->where('deleted_at', null);   
    }

}