<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class PointAllocation extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /*
     * Validation
     */
    public $rules = [
    ];

    // public $belongsToMany = [
    //     'points' => ['Codengine\Awardbank\Models\Point' ,'key' => 'point_id', 'otherKey' => 'id']
    // ];
    public $belongsTo = [
        'points' => ['Codengine\Awardbank\Models\Point','key' => 'point_id', 'otherKey' => 'id'],
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_pointallocations';

    public $timestamps = false;
    
    public function scopeAllocatedTo($query, $pointallocationable_type, $pointallocationable_id)
    {
        return $query->where('pointallocatable_type', $pointallocationable_type)
                    ->where('pointallocatable_id', $pointallocationable_id);
    }
}