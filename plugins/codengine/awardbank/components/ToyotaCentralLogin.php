<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use RainLab\User\Models\User;
use Redirect;


class ToyotaCentralLogin extends ComponentBase
{

    private $user;

    public function componentDetails()
    {
        return [
            'name' => 'Toyota Central Login',
            'description' => 'Toyota Central Login',
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
        $slug = $this->param('slug');
        $user = User::where('external_reference','=',$this->param('slug'))->first();
        if($user){
            Auth::login($user);
            return Redirect::to('/dashboard');
        }
    }
}
