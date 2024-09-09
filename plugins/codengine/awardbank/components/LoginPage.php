<?php namespace Codengine\Awardbank\Components;

/**
 * TODO
 *
 * Change the s3 bucket for staging - is using production atm
 * update the active section in the dashboard menu
 * validate the filesize
 *
 */

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use October\Rain\Database\Attach\Resizer;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Message;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;
use System\Helpers\DateTime;

class LoginPage extends ComponentBase
{

    /** MODELS **/

    const MAX_SPONSOR_LOGOS_PER_ROW = 6;

    public $user;
    public $loginpageform;
    public $infographicComponent;
    public $ethicalsponsorsComponent;
    public $goldsponsorsComponent;
    public $silversponsorsComponent;
    public $bronzesponsorsComponent;
    public $loginpage;

    public function componentDetails()
    {
        return [
            'name' => 'Login Page',
            'description' => 'Login Page editor',
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
        $this->user = Auth::getUser();
        $this->loginpage = \Codengine\Awardbank\Models\LoginPage::where(
            'program_id', '=', $this->user->current_program_id
        )->first();

        if (!$this->loginpage) {
            $this->loginpage = new \Codengine\Awardbank\Models\LoginPage();
            $this->loginpage->program_id = $this->user->current_program_id;
            $this->loginpage->save();
        }

        $this->infographicComponent = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'infographic',
            [
                'deferredBinding' => false
            ]
        );
        $this->infographicComponent->bindModel('infographic', $this->loginpage);

        $this->ethicalsponsorsComponent = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'ethicalSponsorsLogos',
            [
                'deferredBinding' => false
            ]
        );
        $this->ethicalsponsorsComponent->bindModel('ethical_sponsors', $this->loginpage);

        $this->goldsponsorsComponent = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'goldSponsorsLogos',
            [
                'deferredBinding' => false
            ]
        );
        $this->goldsponsorsComponent->bindModel('gold_sponsors', $this->loginpage);

        $this->silversponsorsComponent = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'silverSponsorsLogos',
            [
                'deferredBinding' => false
            ]
        );
        $this->silversponsorsComponent->bindModel('silver_sponsors', $this->loginpage);

        $this->bronzesponsorsComponent = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'bronzeSponsorsLogos',
            [
                'deferredBinding' => false
            ]
        );
        $this->bronzesponsorsComponent->bindModel('bronze_sponsors', $this->loginpage);
    }

    public function onRun()
    {
        $this->coreLoadSequence();
        $this->infographicComponent->singleFile = $this->loginpage->infographic;
        $this->ethicalsponsorsComponent->singleFile = $this->loginpage->ethical_sponsors;
        $this->goldsponsorsComponent->singleFile = $this->loginpage->gold_sponsors;
        $this->silversponsorsComponent->singleFile = $this->loginpage->silver_sponsors;
        $this->bronzesponsorsComponent->singleFile = $this->loginpage->bronze_sponsors;
    }

    /**
    Reusable function call the core sequence of functions to load and render the Cart partial html
     **/

    public function coreLoadSequence()
    {
        $this->generateHtml();
    }

    public function generateHtml()
    {
        $this->loginpageform = $this->renderPartial('@loginpageform', [
            'login_page' => $this->loginpage
        ]);
    }
}
