<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use RainLab\User\Models\User;
use Auth;

class UploaderTest extends ComponentBase
{
    /** MODELS **/

    private $user; 
    public $imagecomponent;
    public $html1;

    public function componentDetails()
    {
        return [
            'name'        => 'UploaderTest',
            'description' => 'UploaderTest'
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
            $this->imagecomponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'fileUploader',
                ['deferredBinding' => false]
            );
            $this->imagecomponent->bindModel('avatar', User::find($this->user->id));
        }
    }

    public function onRun(){
        $this->imagecomponent->fileList = $this->user->avatar;
        $this->imagecomponent->singleFile = $this->user->avatar;
        $this->html1 = $this->renderPartial('@middleman',
            [
                'imagecomponent' => $this->imagecomponent,
            ]
        );  
    } 
}
