<?php namespace Codengine\Awardbank\Components;

use RainLab\User\Models\User as User;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;

class CustomPasswordReset extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $container;
    public $flushtop;
    public $flushbottom;

    public function componentDetails()
    {
        return [
            'name' => 'Post View',
            'description' => 'View A Post',
        ];
    }

    public function defineProperties()
    {
        return [
            'container' => [
                'title' => 'Wrap Component In The Container',
                'type'=> 'checkbox',
                'default' => false,
            ],   
            'flushtop' => [
                'title' => 'Remove Top Padding',
                'type'=> 'checkbox',
                'default' => false,
            ], 
            'flushbottom' => [
                'title' => 'Remove Bottom Padding',
                'type'=> 'checkbox',
                'default' => false,
            ], 
        ];
    }


    public function init()
    {

        $slug = $this->param('slug');
        $random = $this->param('random');
        $this->user = User::where('slug','=', $slug)->where('email_random_string','=',$random)->first();
    }

    public function onRun()
    {
        $this->container = $this->property('container');
        $this->flushtop = $this->property('flushtop');
        $this->flushbottom = $this->property('flushbottom');

    }

    public function onChangePassword(){

        $password = post('password');
        $this->user->password = $password;
        $this->user->password_confirmation = $password;
        $this->user->email_confirmed = 1;
        $this->user->is_activated = 1;
        $this->user->email_random_string = null;
        $this->user->save();
        $this->user->bounceDownRelations();
        Auth::login($this->user);

    }

    public function onSendResetEmail(){

        $user = User::where('email','=',post('email'))->where('deleted_at','=', null)->first();

        if($user == null){
            $result['reset'] = false;
        } else {
            $user->sendPwordResetEmail();
            $result['reset'] = true;
        }

        return $result;

    }

}
