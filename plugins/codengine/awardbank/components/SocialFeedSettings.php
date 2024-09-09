<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Auth;
use Codengine\Awardbank\Models\SocialPost;
use Codengine\Awardbank\Models\SocialPostResponse;
use Event;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use October\Rain\Database\Attach\Resizer;
use October\Rain\Exception\AjaxException;

/**
 * Class SocialFeedSettings
 * @package Codengine\Awardbank\Components
 */

class SocialFeedSettings extends ComponentBase
{
    private $user;
    private $program;
    private $topbannerComponent;
    private $bottombannerComponent;

    public function componentDetails()
    {
        return [
            'name'        => 'Social Feed Settings',
            'description' => 'Social Feed Settings'
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
        if ($this->user && $this->user->currentProgram) {
            $this->program = $this->user->currentProgram;

            $this->topbannerComponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'topBannerComponent',
                [
                    'deferredBinding' => false
                ]
            );
            $this->topbannerComponent->bindModel('social_feed_top_banner', $this->program);

            $this->bottombannerComponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'bottomBannerComponent',
                [
                    'deferredBinding' => false
                ]
            );
            $this->bottombannerComponent->bindModel('social_feed_bottom_banner', $this->program);
        }
    }

    public function onRun()
    {
        $this->coreLoadSequence();
        $this->topbannerComponent->singleFile = $this->program->social_feed_top_banner;
        $this->bottombannerComponent->singleFile = $this->program->social_feed_bottom_banner;
    }

    public function onRender()
    {
        $this->page['default_start_date'] = date('Y-m-d', strtotime('- 30 days'));
        $this->page['default_end_date'] = date('Y-m-d');
    }

    public function coreLoadSequence()
    {
        $this->generateHtml();
    }

    public function generateHtml()
    {
        $this->page['user'] = $this->user;
    }
}
