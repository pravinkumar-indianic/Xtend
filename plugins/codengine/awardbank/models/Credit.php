<?php namespace Codengine\Awardbank\Models;

use Model;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Remote\Request;
use XeroPHP\Remote\URL;
use System\Helpers\DateTime;
/**
 * Model
 */
class Credit extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'value',
        'name',
        'external_reference',
        'user_id',
        'program_id',
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    public $hasMany = [
        'points' => 'Codengine\Awardbank\Models\Point'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_credits';


    public function afterCreate(){

        $i = 1;

        while($i <= $this->value){

            $point = new Point();
            $point->credit_id = ($this->id);
            $point->spent = 0;
            $point->pending = 0;
            $point->transaction_id = 0;
            $point->save();
            $i++;

        }

        $this->save();

        $program = Program::find($this->program_id);

        $invoice = new BillingInvoice;
        $invoice->program_id = $this->program_id;
        $invoice->xero_amountdue = $this->value;
        $invoice->billing_contact_id = $program->billingcontact->id;
        $invoice->type = 'credit';
        $invoice->save();

    }


    public function beforeSave(){

        $this->spent_points = $this->points()->where('spent','=',1)->count();

        $this->allocated_points = $this->points()->has('allocations')->count();

        $allPoints = $this->points()->count();

        $this->unallocated_points = $this->points()->doesntHave('allocations')->count();

    }

    public function getAllocateRecordOptions(){

        if(post('program_id')){

            $program = Program::where('id','=', post('program_id'))->with('regions.teams.users')->first();

        }   else {

            $program = Program::where('id','=', $this->program_id)->with('regions.teams.users')->first();

        }

        if(post('CreditAllocateArray')){

            $allocateArray = post('CreditAllocateArray');

            $type = $allocateArray['allocate_type'];

        } else {

            $type = '1';
        }

        if($type == '1'){

            $array[$program->id] = $program->name;

        } elseif($type == '2'){

            foreach($program->regions as $region){

                $array[$region->id] = $region->name;     
                          
            }

        } elseif($type == '3'){

            foreach($program->regions as $region){

                foreach($region->teams as $team){

                    $array[$team->id] = $team->name;   

                }  
                          
            }

        } elseif($type == '4'){

            foreach($program->regions as $region){

                foreach($region->teams as $team){

                    foreach($team->users as $user){

                        $array[$user->id] = $user->full_name;   
                    }
                }               
            }
        }

        return $array;
    }

}