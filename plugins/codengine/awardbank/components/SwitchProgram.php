<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Program;
use Auth;
use Redirect;

class SwitchProgram extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $categories;
    public $html;

    public function componentDetails()
    {
        return [
            'name' => 'Switching Program Module',
            'description' => 'Switching Program Module',
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
        if($this->user){
            $this->user = $this->user->load(['programs','currentProgram','currentProgram.header_icon','avatar']);
        }    

    }

    public function onRun()
    {
        $this->addJs('/plugins/codengine/awardbank/assets/js/SwitchProgram130819.js');
        $this->coreLoadSequence();
    }

    /**
        Run the core functions for processing variables and generating html.
    **/

    public function coreLoadSequence()
    {
        $this->buildHtml();
    }


    /**
        Render the html partials to pass back into public vars.
    **/

    public function buildHtml()
    {
        $this->html = $this->renderPartial('@dropdown',
            [
                'programs' => $this->user->programs, 
                'currentProgram' => $this->user->currentProgram,
            ]
        );  
    }

    /**
        AJAX Requests
    **/

    /**
        Catch the post('programid') - switch user program vars and rerun points from user model extension.
    **/

    public function onSwitchProgram()
    {
        $programID = post('programid');
        try{
            $this->user->switchProgram($programID);
            $this->user->runMyPoints(true);
            return Redirect::refresh();
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}