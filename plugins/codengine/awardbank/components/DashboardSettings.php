<?php namespace Codengine\Awardbank\Components;

/**
 * TODO
 *
 * Change the s3 bucket for staging - is using production atm
 * update the active section in the dashboard menu
 * validate the filesize
 *
 */

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Controllers\DashboardSettings as DashboardSettingsController;
use Codengine\Awardbank\Models\Result;
use Codengine\Awardbank\Models\ResultImport;
use Codengine\Awardbank\Models\ResultType;
use Illuminate\Support\Facades\Input;
use RainLab\User\Models\User;
use Storage;
use Config;
use Auth;
use Redirect;

class DashboardSettings extends ComponentBase
{

    /** MODELS **/
    private $limitperimport = 200;
    private $dashboardsetting;
    private $program;
    private $user;
    private $imagecomponent;

    public function componentDetails()
    {
        return [
            'name' => 'Dashboard Settings',
            'description' => 'Dashboard Settings',
        ];
    }

    public function init()
    {
        $this->user = Auth::getUser();
        if ($this->user) {
            $this->program = $this->user->currentProgram;
            $this->dashboardsetting = \Codengine\Awardbank\Models\DashboardSettings::where(
                'program_id', '=', $this->program->id
            )->first();

            $this->imagecomponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'sliderImages',
                [
                    'deferredBinding' => false
                ]
            );
            $this->imagecomponent->bindModel('slider_images', $this->program);
        }
    }

    public function onRun()
    {
        $this->page['dashboardsettings'] = $this->dashboardsetting;
        //$this->imagecomponent->fileList = $this->program->slider_images;
        $this->imagecomponent->singleFile = $this->program->slider_images;
    }
}
