<?php namespace Codengine\Awardbank\Models;

use Codengine\Awardbank\Models\XeroAPI;
use Model;


/**
 * Model
 */
class BillingContact extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sortable;

    protected $dates = ['deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_billingcontact';

    public $hasOne = [

        'organization' => ['Codengine\Awardbank\Models\Organization', 'key' => 'billingcontact_id', 'otherKey' => 'id'],
        'program' => ['Codengine\Awardbank\Models\Program', 'key' => 'billingcontact_id', 'otherKey' => 'id'],
        'region' => ['Codengine\Awardbank\Models\Program', 'key' => 'billingcontact_id', 'otherKey' => 'id'],
        'team' => ['Codengine\Awardbank\Models\Program', 'key' => 'billingcontact_id', 'otherKey' => 'id'],
        'user' => ['Codengine\Awardbank\Models\Program', 'key' => 'billingcontact_id', 'otherKey' => 'id'],

    ];

    public $hasMany = [
        'transactions' => ['Codengine\Awardbank\Models\Transaction', 'key' => 'billing_contact_id', 'otherKey' => 'id'],
        'billinginvoice' => ['Codengine\Awardbank\Models\Transaction', 'key' => 'billing_contact_id', 'otherKey' => 'id'],

    ];


     public function beforeCreate(){
        if(!isset($this->sync)){
            $xero = XeroAPI::getXero();
            $contact = new \XeroPHP\Models\Accounting\Contact($xero);
            $contact->setContactNumber($this->id)
            ->setName($this->name)
            ->setFirstName($this->firstname)
            ->setLastName($this->lastname)
            ->setEmailAddress($this->emailaddress);
            $contact->save();
            $this->xero_id = $contact->ContactID;
            $this->contactstatus = $contact->ContactStatus;

        } else {
            unset($this->sync);
        }

    }

    public function beforeUpdate(){

        if(!isset($this->sync)){
            $xero = XeroAPI::getXero();
            $contact = $xero->loadByGUID('Accounting\\Contact', $this->xero_id);
            $contact->setContactNumber($this->id)
            ->setName($this->name)
            ->setFirstName($this->firstname)
            ->setLastName($this->lastname)
            ->setEmailAddress($this->emailaddress);
            $contact->save();

        } else {
            unset($this->sync);
        }
    }
}
