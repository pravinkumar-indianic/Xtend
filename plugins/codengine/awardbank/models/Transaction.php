<?php namespace Codengine\Awardbank\Models;

use Model;
use Config;

/**
 * Model
 */
class Transaction extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id','user_id','paymentgateway_id','value','success'];

    public $belongsTo  = [

        'billingcontact' => ['Codengine\Awardbank\Models\BillingContact',  'key' => 'billing_contact_id', 'otherKey' => 'id'],
        'program' =>  ['Codengine\Awardbank\Models\Program'],
        'user' =>  ['RainLab\User\Models\User'],
    ];

    public $hasOne = [

        'billinginvoice' => ['Codengine\Awardbank\Models\BillingInvoice',  'key' => 'transaction_id', 'otherKey' => 'id'],
    ];

    public $hasMany = [
        //'points' => 'Codengine\Awardbank\Models\Point'
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_transactions';

    public function beforeCreate(){
        //round up the transaction value with 2 decimal places precision
        $this->value = (ceil($this->value * 100))/100;
        $charge = $this->value;
        $this->paymentgateway_id = 0;

        $config = Config::get('codengine.awardbank::payway');
        $publishableAPI = $config['publishableAPI'];
        $secretAPI = $config['secretAPI'];

        $client = new \GuzzleHttp\Client();
        $customernumber = 'XTEND-'.$this->billing_contact_id;

        $res = $client->post('https://api.payway.com.au/rest/v1/transactions',
            [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'auth' =>  [$secretAPI, ''],

                'form_params' => [
                    'transactionType' => 'payment',
                    'principalAmount' => $charge,
                    'currency' => 'aud',
                    'merchantId' => '24097800',
                    'customerNumber' => $customernumber,
                ],
        ]);

        $status = $res->getStatusCode(); // 200
        $body = $res->getBody(); // { "type": "User", ....

        if($status == 201 || $status == 200){
            $this->success = 1;
        }
    }

    public function afterCreate(){
        if($this->success == true && $this->type == 'points_purchase'){

            $newLedger = new PointsLedger;
            $newLedger->points_value = $this->value * $this->program->scale_points_by;
            $newLedger->user_id = $this->user_id;
            $newLedger->program = $this->program_id;
            $newLedger->transaction_id = $this->id;
            $newLedger->type = 1;
            $newLedger->save();

        }

        if($this->success == true && $this->type == 'points_purchase_program'){

            $newLedger = new PointsLedger;
            $newLedger->points_value = $this->value * $this->program->scale_points_by;
            $newLedger->user_id = $this->user_id;
            $newLedger->program = $this->program_id;
            $newLedger->transaction_id = $this->id;
            $newLedger->type = 5;
            $newLedger->save();

        }

        if($this->success == true && $this->billinginvoice == null){
            $billinginvoice = new BillingInvoice;
            $billinginvoice->billing_contact_id = $this->billing_contact_id;
            $billinginvoice->program_id = $this->program_id;
            $billinginvoice->transaction_id = $this->id;
            $billinginvoice->xero_amountdue = $this->value;
            $billinginvoice->type = $this->type;
            $billinginvoice->save();
        }
    }
}
