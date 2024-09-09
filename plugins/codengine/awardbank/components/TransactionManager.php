<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Product;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\BillingContact;
use Codengine\Awardbank\Models\Transaction;
use Auth;
use Session;
use Log;
use Redirect;
use Config;

class TransactionManager extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $value;
    private $dollarvalue;
    private $limit;
    private $billingcontact;
    private $product;
    public $html1;

    public function componentDetails()
    {
        return [
            'name' => 'Transaction Manager',
            'description' => 'Transaction Manager',
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }


    public function init()
    {
        $this->user = Auth::getUser();   
    }

    public function onRun()
    {
        $this->addJs('/plugins/codengine/awardbank/assets/js/TransactionManager151019.js');
        $this->coreLoadSequence();
    }

    public function coreLoadSequence()
    {
        $this->getModels();
        $this->modelFactory();
        $this->generateHtml();
    }

    public function getModels()
    {
        $this->billingcontact = $this->user->billingcontact;
        $this->limit = $this->user->currentProgram->points_limit;
        if($this->param('value')){
            $this->value = $this->param('value');
        } else {
            $this->value = 1000;
        }
        if($this->limit >= 1){
            if($this->value >= $this->limit){
                $this->value = $this->limit;
            }
        }
        $this->dollarvalue = $this->value / $this->user->currentProgram->scale_points_by;
        $this->product = Product::find($this->param('product'));
    }

    public function modelFactory()
    {

    }

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@buypoints',
            [
                'billingcontact' => $this->billingcontact,
                'value' => $this->value,
                'dollarvalue' => $this->dollarvalue,
                'limit' => $this->limit,
            ]
        );  
    }

    public function testPost($input)
    {
        if($input != '' && !empty($input)){
            return true;
        } else {
            return false;
        }
    }

    public function onCreateTransaction()
    {
        $transaction = new Transaction;
        $transaction->value = post('dollarvalue');
        $transaction->type = 'points_purchase';
        $transaction->user_id = $this->user->id;
        $transaction->program_id = $this->user->current_program_id;
        $transaction->billing_contact_id = $this->user->billingcontact_id;
        $transaction->save();
        $this->pageCycle();
        if($this->product){
            return Redirect::to('/rewards/'.$this->product->slug);
        } else {
            return Redirect::to('/rewards/category/default');
        }
    }
}
