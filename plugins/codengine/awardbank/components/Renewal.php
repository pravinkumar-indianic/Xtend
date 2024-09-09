<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Codengine\Awardbank\Models\Program as Program;
use Codengine\Awardbank\Models\Transaction as Transaction;
use RainLab\User\Models\User;
use Carbon\Carbon;
use Event;

class Renewal extends ComponentBase
{

    /** MODELS **/

    public $billingDay;
    public $todayBilling;

    public function componentDetails()
    {
        return [
            'name' => 'Renewal',
            'description' => 'Manual Renewal',
        ];
    }

    public function defineProperties()
    {
        return [
  
        ];
    }


    public function init()
    {


    }

    public function onRun()
    {
        $this->getRenewals();
    }

    public function getRenewals()
    {

        $current_date = Carbon::now();
        $this->billingDay = $current_date->day;
        $this->todayBilling = Program::where('xero_renewal_day_of_month','=',$this->billingDay)->get();        

    }

    public function onRunBilling()
    {
        $this->getRenewals();
        foreach($this->todayBilling as $program){
            if($program->billingcontact){
                $program->save();
                $transaction = new Transaction;
                if($program->xero_renewal_member_count <= 10){
                    $transaction->value = 10;
                } else {
                    $transaction->value = $program->xero_renewal_member_count * $program->xero_renewal_price_plan;
                }
                $transaction->type = 'renewal';
                $transaction->program_id = $program->id;
                $transaction->billing_contact_id = $program->billingcontact->id;
                $transaction->save();
            }
        }
    }

    public function onSendTenure()
    {
        Event::fire('xtend.sendTenureEmail'); 
    }

}
