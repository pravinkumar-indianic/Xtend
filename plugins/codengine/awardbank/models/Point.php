<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Point extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id','transaction_id', 'credit_id', 'spent'];

    public $belongTo = [
        'credit' => ['Codengine\Awardbank\Models\Credit'],
        'transaction' => ['Codengine\Awardbank\Models\Transaction'],
    ];


    public $belongsToMany = [
        'orders' => ['Codengine\Awardbank\Models\Order', 'table' => 'codengine_awardbank_order_points']
    ];

    public $hasMany = [
        'allocations' => 'Codengine\Awardbank\Models\PointAllocation'
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_points';

    public $timestamps = false;

    public function scopeNotSpent($query)
    {
        return $query->where('spent', FALSE);
    }

    public function scopeByTransaction($query, $tid)
    {
        return $query->where('transaction_id', $tid);
    }

    public function scopeByCredit($query, $cid)
    {
        return $query->where('credit_id', $cid);
    }

    public function updateAllocation($targettype, $targetid, $allocator){

        $previousallocation = $this->allocations()->where('current_allocation','=', true)->first();
        $previousallocation->current_allocation = false;
        $previousallocation->save();

        $allocation = new PointAllocation();
        $allocation->point_id = $this->id;
        $allocation->pointallocatable_type = $targettype;
        $allocation->pointallocatable_id = $targetid;
        $allocation->previous_allocation_id = $previousallocation->id;
        $allocation->current_allocation = 1;
        $allocation->allocator = $allocator;
        $allocation->save();

    }
}