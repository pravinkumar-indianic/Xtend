<?php namespace Codengine\Awardbank\Models;

use Model;

/**
 * Model
 */
class Address extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'country_id',
        'state',
        'city',
        'unit_number',
        'street_number',
        'street_name',
        'street_type',
        'suburb_name',
        'postcode',
        'lat',
        'lng'
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_addresses';

    public $hasMany = [
        'suppliers' => 'Codengine\Awardbank\Models\Supplier',
        'users' => 'RainLab\User\Models\User',
        'orders' => ['Codengine\Awardbank\Models\Order', 'key' => 'shipping_address_id', 'otherKey' => 'id'],
        'programs' => 'Codengine\Awardbank\Models\Program',    
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

    public function beforeCreate()
    {
        $this->country_id = 1;
    }

    public function beforeSave(){

        $string = '';
        if($this->business_name != null && $this->business_name != ''){
            $string .= 'BUSINESS: '.$this->business_name.' - ';
        }
        if($this->attn_name != null && $this->attn_name != ''){
            $string .= 'ATTN: '.$this->attn_name.' - ';
        }
        if($this->floor != null && $this->floor != ''){
            $string .= 'LEVEL: '.$this->floor.' - ';
        }
        if($this->unit_number != null && $this->unit_number != ''){
            $string .= 'UNIT: '.$this->unit_number.' - ';
        }
        $string .= $this->street_number.' '.$this->street_name.' '.$this->street_type.' '.$this->city.' '.$this->suburb_name.' '.$this->postcode.' '.$this->state.' '.$this->country;
        $this->full_address = $string;
    }

    public function getMyFulladdressAttribute() 
    {   
        return $this->unit_number.' '.$this->street_number.' '.$this->street_name.' '.$this->street_type.' '.$this->city.' '.$this->suburb_name.' '.$this->postcode.' '.$this->state.' '.$this->country;
    }    
    public function getPOaddressAttribute() 
    {   

        $string = '';

        if($this->floor != null && $this->floor != ''){
            $string .= 'Level '.$this->floor;
        }
        if($this->unit_number != null && $this->unit_number != ''){
            $string .= ' Unit '.$this->unit_number;
        }
        if($this->street_number != null && $this->street_number != ''){
            $string .= ' '.$this->street_number;
        }
        if($this->street_name != null && $this->street_name != ''){
            $string .= ' '.$this->street_name;
        }
        if($this->street_type != null && $this->street_type != ''){
            $string .= ' '.$this->street_type;
        }
        if($this->city != null && $this->city != ''){
            $string .= ' '.$this->city;
        }
        if($this->suburb_name != null && $this->suburb_name != ''){
            $string .= ' '.$this->suburb_name;
        }
        if($this->postcode != null && $this->postcode != ''){
            $string .= ' '.$this->postcode;
        }
        if($this->state != null && $this->state != ''){
            $string .= ' '.$this->state;
        }
        if($this->country != null && $this->country != ''){
            $string .= ' '.$this->country;
        }
        return $string;
    }
}