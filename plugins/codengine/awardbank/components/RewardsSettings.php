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
use Storage;
use Config;
use Auth;
use Redirect;

class RewardsSettings extends ComponentBase
{

    /** MODELS **/
    private $program;
    private $user;
    public $dashboardimage1component;
    public $dashboardimage2component;
    public $rewardsimagecomponent;

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

            $this->dashboardimage1component = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'dashboard_image_first',
                [
                    'deferredBinding' => false
                ]
            );
            $this->dashboardimage1component->bindModel('dashboard_image_first', $this->program);

            $this->dashboardimage2component = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'dashboard_image_second',
                [
                    'deferredBinding' => false
                ]
            );
            $this->dashboardimage2component->bindModel('dashboard_image_second', $this->program);

            $this->rewardsimagecomponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'reward_banner_image',
                [
                    'deferredBinding' => false
                ]
            );
            $this->rewardsimagecomponent->bindModel('reward_banner_image', $this->program);
        }
    }

    public function onRun()
    {
        $this->dashboardimage1component->singleFile = $this->program->dashboard_image_first;
        $this->dashboardimage2component->singleFile = $this->program->dashboard_image_second;
        $this->rewardsimagecomponent->singleFile = $this->program->reward_banner_image;
    }
}
