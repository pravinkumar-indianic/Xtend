<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class PermissionAccessAllocation extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    //use \October\Rain\Database\Traits\SoftDelete;

    //protected $dates = ['deleted_at'];
    public $timestamps = false;

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_permission_access_allocation';

    public $belongsTo = [
        'permission' => 'Codengine\Awardbank\Models\Permission'
    ];

    public $morphTo = [
        'permissionaccessallocatable' => []
    ];    

}