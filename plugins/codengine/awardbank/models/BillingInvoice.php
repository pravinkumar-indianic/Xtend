<?php namespace Codengine\Awardbank\Models;

use Codengine\Awardbank\Models\Settings;
use Codengine\Awardbank\Models\XeroAPI;
use Carbon\Carbon;
use Model;

/**
 * Model
 */
class BillingInvoice extends Model
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
    public $table = 'codengine_awardbank_billinginvoice';

    public $belongsTo = [
        'program' => 'Codengine\Awardbank\Models\Program',
        'user' => 'RainLab\User\Models\User',
        'billingcontact' => ['Codengine\Awardbank\Models\BillingContact',  'key' => 'billing_contact_id', 'otherKey' => 'id'],
        'transactions' => ['Codengine\Awardbank\Models\Transaction', 'key' => 'transaction_id', 'otherKey' => 'id'],
    ];


    public function afterCreate(){

            $xero = XeroAPI::getXero();

            if($this->billingcontact != null){
                $billingcontactid = $this->billingcontact->xero_id;
                $contact = $xero->loadByGUID('Accounting\\Contact', $billingcontactid);
                $name = $this->program->xero_job_code_suffix;
            } else if($this->program != null){
                $billingcontactid = $this->billingcontact->xero_id;
                $contact = $xero->loadByGUID('Accounting\\Contact', $billingcontactid);
                $name = $this->program->xero_job_code_suffix;
            }

            $cardfee = Settings::get('card_rate');
            if($this->billingcontact->payment_terms == 'amex'){
                $cardfee = Settings::get('amex_rate');
            }

            if($contact != null){
                $invoicenumber = 'Xtend-'.$this->id;
                if($name == null || $name == ''){
                    $name = 'EMPTY';
                }
                $trackingcategory = $xero->load('Accounting\\TrackingCategory')
                ->where('Name', 'Job')
                ->execute();
                $jobcodes = $trackingcategory->first();
                if($jobcodes == null){
                    $jobcodes = new \XeroPHP\Models\Accounting\TrackingCategory($xero);
                    $jobcodes->setName('Job');
                    $jobcodes->save();
                }
                $newOption = true;
                if($jobcodes->getOptions() != null){
                    foreach($jobcodes->getOptions() as $option){
                        $optionname = $option->getName();
                        if($optionname == $name){
                            $usedoption = $option;
                            $newOption = false;
                        }
                    }
                }
                if($newOption == true){
                    $usedoption = new \XeroPHP\Models\Accounting\TrackingCategory\TrackingOption($xero);
                    $usedoption->setName($name);
                    $usedoption->setStatus('ACTIVE');
                    $jobcodes->addOption($usedoption);
                    $jobcodes->save();
                }
                $jobcodes->setOption($name);

                $invoice = new \XeroPHP\Models\Accounting\Invoice($xero);
                $invoice
                ->setType('ACCREC')
                ->setLineAmountType('Inclusive')
                ->setDueDate(Carbon::now())
                ->setStatus('AUTHORISED')
                ->setInvoiceNumber($invoicenumber)
                ->setReference('Xtend')
                ->setBrandingThemeID('ed9f4406-a90e-4e27-89bc-c888f0cbb19c')
                ->setContact($contact);

                if($this->type == 'renewal'){

                    $lineItem = new \XeroPHP\Models\Accounting\Invoice\LineItem($xero);
                    $lineItem->setDescription('Xtend User Fees For Prior Month');
                    $lineItem->setQuantity('1');
                    $lineItem->setUnitAmount(number_format((float)$this->xero_amountdue, 2, '.', ''));
                    $lineItem->setAccountCode('208');
                    //$lineItem->setAccountCode('201');
                    $lineItem->setItemCode('Administration');
                    //$lineItem->setItemCode('TSS - Black: T-Shirt Small Black');
                    $lineItem->setTaxType('OUTPUT');
                    $lineItem->addTracking($jobcodes);
                    $lineItem->save();
                    $invoice->addLineItem($lineItem);
                }

                if($this->type == 'points_purchase' || $this->type == 'points_purchase_program'){

                    $lineItem = new \XeroPHP\Models\Accounting\Invoice\LineItem($xero);
                    $lineItem->setDescription('Xtend Points Purchase');
                    $lineItem->setQuantity('1');
                    $lineItem->setUnitAmount(number_format((float)$this->xero_amountdue, 2, '.', ''));
                    $lineItem->setAccountCode('204');
                    //$lineItem->setAccountCode('201');
                    $lineItem->setItemCode('Reward Income');
                    //$lineItem->setItemCode('TSS - Black: T-Shirt Small Black');
                    $lineItem->setTaxType('OUTPUT');
                    $lineItem->addTracking($jobcodes);
                    $lineItem->save();
                    $invoice->addLineItem($lineItem);

                }

                $lineItem = new \XeroPHP\Models\Accounting\Invoice\LineItem($xero);
                $lineItem->setDescription('Credit Card Fee');
                $lineItem->setQuantity('1');
                $lineItem->setUnitAmount(number_format((float)($this->xero_amountdue * ($cardfee - 1)), 2, '.', ''));
                $lineItem->setAccountCode('201');
                //$lineItem->setAccountCode('201');
                $lineItem->setItemCode('Reward Income');
                //$lineItem->setItemCode('TSS - Black: T-Shirt Small Black');
                $lineItem->setTaxType('OUTPUT');
                $lineItem->addTracking($jobcodes);
                $lineItem->save();
                $invoice->addLineItem($lineItem);

                $invoice->save();

                $accounts = $xero->load('Accounting\\Account')
                ->where('Code', '601-2')
                ->execute();

                $account = $accounts->first();

                if($this->type != 'credit'){
                    $payment = new \XeroPHP\Models\Accounting\Payment($xero);
                    $payment->setInvoice($invoice);
                    $payment->setAccount($account);
                    $payment->setAmount(number_format((float)($this->xero_amountdue * $cardfee), 2, '.', ''));
                    //$payment->setIsReconciled('true');
                    $payment->save();
                    $invoice->sendEmail();
                }



                //dump($invoice);

                $this->xero_id = $invoice->InvoiceID;
                $this->xero_amountdue = $invoice->AmountDue;
                $this->xero_contact = $billingcontactid;
                $this->xero_duedate = $invoice->DueDate;
                $this->xero_date = $invoice->Date;
                $this->xero_status = $invoice->Status;
                $this->xero_subtotal = $invoice->SubTotal;
                $this->xero_totaltax = $invoice->TotalTax;
                $this->save();
            }
    }

}
