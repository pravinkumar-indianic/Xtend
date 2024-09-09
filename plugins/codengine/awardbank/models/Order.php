<?php namespace Codengine\Awardbank\Models;

use Model;
use Mail;
use Event;
use RainLab\User\Models\User;

/**
 * Model
 */
class Order extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id','delivery_id'];

    // public $hasMany = [
    //     'points' => ['Codengine\Awardbank\Models\Point', 'table' => 'codengine_awardbank_order_points'],

    //     'products' => ['Codengine\Awardbank\Models\Product', 'table' => 'codengine_awardbank_order_products'],        
    // ];

    public $belongsToMany = [
        'products' => [ 
            'Codengine\Awardbank\Models\Product', 
            'table' => 'codengine_awardbank_order_products',
            'key'      => 'order_id',
            'otherKey' => 'product_id'
        ]
    ];

    public $belongsTo = [
        'orderplacer' => [
            'RainLab\User\Models\User', 
            'key' => 'user_id'
        ],
        'shippingaddress' => [
            'Codengine\Awardbank\Models\Address',
            'key' => 'shipping_address_id'
        ],
        'program' => [
            'Codengine\Awardbank\Models\Program',
            'key' => 'order_program_id'
        ],
    ];

    public $hasMany = [
        'orderlineitems' => [
            'Codengine\Awardbank\Models\OrderLineitem'
        ],
        'pointsledgers' => [
            'Codengine\Awardbank\Models\PointsLedger'
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
    public $table = 'codengine_awardbank_orders';

    public function beforeCreate()
    {
        $this->order_id = Event::fire('xtend.getRandomString','',true);
    }

    public function afterCreate()
    {
        $pointAllocation = $this->allocatePointsToOrder();
        if (!$pointAllocation instanceof \Exception) {
            $this->emptyUserCart();
        }
        $this->newOrderEmail();
    }

    public function allocatePointsToOrder()
    {
        try{

            $pointsledger = new PointsLedger();
            $pointsledger->points_value = $this->points_value;
            $pointsledger->user_id = $this->orderplacer->id;
            $pointsledger->program_id = $this->program->id;
            $pointsledger->order_id = $this->id;
            $pointsledger->type = 4;
            $pointsledger->save();

            $pointsledger = new PointsLedger();
            $pointsledger->points_value = $this->points_value;
            $pointsledger->user_id = $this->orderplacer->id;
            $pointsledger->program_id = $this->program->id;
            $pointsledger->order_id = $this->id;
            $pointsledger->type = 2;
            return $pointsledger->save();

        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function emptyUserCart()
    {
        $userCart = $this->orderplacer->cart_array;
        if (!empty($userCart[$this->program->id])) {
            $userCart[$this->program->id] = [];
        }

        $user = User::find($this->orderplacer->id);
        $user->cart_array = [];
        $user->save();
    }

    public function newOrderEmail()
    {

    }
}
