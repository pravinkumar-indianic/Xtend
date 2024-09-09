<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class AccessManagerAllocation extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    protected $fillable = ['user_id','managable_id','managable_type'];

    /*
     * Validation
     */
    
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_access_manager_allocation';
}