<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class PointsLedger extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_points_ledger';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /** RELATIONSHIPS **/

    public $belongsTo = [
        'program' => [
            'Codengine\Awardbank\Models\Program',
        ],
        'team' => [
            'Codengine\Awardbank\Models\Team',
        ],
        'order' => [
            'Codengine\Awardbank\Models\Order',
        ],
        'user' => [
            'RainLab\User\Models\User',
        ],
        'product' => [
            'Codengine\Awardbank\Models\Product',
        ],
        'transaction' => [
            'Codengine\Awardbank\Models\Transaction',
        ],
    ];

    /** PROCESS FUNCTION **/

    public function beforeSave()
    {
        $this->processDollarValue();
    }

    /** GET FUNCTIONS FOR TRANSLATION **/

    public function getTypeOptions()
    {
        return [
            '0'=> 'Fixed Value',
            '1'=> 'Addition Value',
            '2'=> 'Subtraction Value',
            '3' => 'In Cart',
            '4' => 'Out Cart',
            '5' => 'Program Points Addition',
            '6' => 'Program Points Transfer',
            '7' => 'Program Points Return',
            '8' => 'Program Points Subtraction',
        ];
    }

    public function getTypeAttribute()
    {
        $value = array_get($this->attributes, 'type');
        if($value){
            return array_get($this->getTypeOptions(), $value);
        }
    }

    /** FILTER SCOPES **/

    public function scopeFilterPrograms($query, $filter)
    {
        $query->whereIn('program_id', $filter);
    }

    public function scopeFilterUsers($query, $filter)
    {
        $query->whereIn('user_id', $filter);
    }

    public function scopeFilterOrders($query, $filter)
    {
        $query->whereIn('order_id', $filter);
    }

    /** CODENGINE FUNCTION **/

    public function processDollarValue()
    {
        if($this->program){
            $this->dollar_value = $this->points_value / $this->program->scale_points_by;
        } else {
            $this->dollar_value = 0;
        }
    }
}
