<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Auth;

class CustomStyles extends ComponentBase
{

    /** MODELS **/

    public $user;

    public function componentDetails()
    {
        return [
            'name' => 'Custom Styles Module',
            'description' => 'Custom Styles Module',
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
            $this->user = $this->user->load(['currentProgram']);
        }    

    }
}