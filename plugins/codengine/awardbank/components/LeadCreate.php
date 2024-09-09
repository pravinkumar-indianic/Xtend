<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Lead;
use Auth;

class LeadCreate extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'Create A New Lead',
            'description' => 'Create A New Lead',
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

    }


    public function onCreateLead()
    {
        try {
            $lead = new Lead;
            if($this->testPost(post('First_Name')) == true){
                $lead->name = post('First_Name');
            }
            if($this->testPost(post('Last_Name')) == true){
                $lead->surname = post('Last_Name');
            }
            if($this->testPost(post('Email')) == true){
                $lead->email = post('Email');
            }
            if($this->testPost(post('Phone')) == true){
                $lead->phone = post('Phone');
            }
            $lead->save();
            $result['html']['.modal .content'] = '';
            $result['html']['.modal .actions'] = '';
            $result['updatesucess'] = "Details Sent Succesfully. Someone from EVT will be in touch in 1 - 2 Business Days.";
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function testPost($input)
    {
        if($input != '' && !empty($input)){
            return true;
        } else {
            return false;
        }
    }
}