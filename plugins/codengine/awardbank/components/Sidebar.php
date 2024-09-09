<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Auth;

class Sidebar extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $manager = false;
    public $html1;

    public function componentDetails()
    {
        return [
            'name' => 'Sidebar Module',
            'description' => 'Sidebar Module',
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
            $this->user = $this->user->load(['currentProgram','currentProgram.managers']);
            $this->manager = $this->user->currentProgram ? $this->user->currentProgram->checkIfManager($this->user) : false;
        }
    }

    public function onRun()
    {
        $this->coreLoadSequence();
    }

    /**
        Run the core functions for processing variables and generating html.
    **/

    public function coreLoadSequence()
    {
        $this->getModels();
        $this->buildHtml();
    }

    public function getModels()
    {

    }

    public function buildHtml()
    {
        $this->html1 = $this->renderPartial('@sidebar',
            [
                'user' => $this->user,
                'manager' => $this->manager,
            ]
        );
    }

}
