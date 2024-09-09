<?php namespace Codengine\Awardbank\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class Team extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    use \October\Rain\Database\Traits\NestedTree;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    protected $fillable = [
        'name',
        'description',
        'primary_color',
        'secondary_color',
        'program_id',
        'billingcontact_id'
    ];       

    /*
     *     */

    public $rules = [
        'name'    => 'required|string',
        'description'   => 'nullable|string',
        'primary_color' => 'required|string',
        'secondary_color' => 'required|string',
        'external_reference' => 'nullable|string',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_teams';

    public $belongsTo = [
        'parent' => ['Codengine\Awardbank\Models\Team', 'key' => 'parent_id', 'otherKey' => 'id'],
        'program' => ['Codengine\Awardbank\Models\Program'],
        'billingcontact' => ['Codengine\Awardbank\Models\BillingContact', 'key' => 'billingcontact_id', 'otherKey' => 'id'], 
    ];

    public $belongsToMany = [
        'users' => ['Rainlab\User\Models\User','table' => 'codengine_awardbank_u_t'],
        'regions' => ['Codengine\Awardbank\Models\Region','table' => 'codengine_awardbank_t_r'],
        'managers' => ['Rainlab\User\Models\User','table' => 'codengine_awardbank_u_tm'],  
    ];

    public $hasMany = [
        'nominations' => 'Codengine\Awardbank\Models\Nomination',
        'results' => 'Codengine\Awardbank\Models\Result', 
        'pointsledgers' => ['Codengine\Awardbank\Models\PointsLedger'],
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

    public function beforeSave(){
        $this->slugAttributes();
    } 

    /**
        Filter Scopes

    **/

    public function scopeIsActive($query)
    {
        return $query->where('deleted_at', null);   
    }

    public function scopeFilterHasParent($query, $response){
        $query->whereHas('parent', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasProgram($query, $response){
        $query->whereHas('program', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasBillingContact($query, $response){
        $query->whereHas('billingcontact', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasUsers($query, $response){
        $query->whereHas('users', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasManagers($query, $response){
        $query->whereHas('managers', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }
}