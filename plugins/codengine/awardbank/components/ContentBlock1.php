<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;

class ContentBlock1 extends ComponentBase
{

    /** MODELS **/

    public $background;
    public $headingText;
    public $buttonText;
    public $buttonLink;
    public $program;
    public $btnBorderColor;
    public $btn_text_color;
    public $titleColor;
    public $btnBackgroundColor;

    public $overrideHoverButton;


    public function componentDetails()
    {
        return [
            'name' => 'Content Block 1',
            'description' => 'Creates a large content block',
        ];
    }

    public function defineProperties()
    {
        return [
            'imageNamesArray' => [
                'title'             => 'Select Background Image',
                'type'              => 'dropdown',
            ],
            'headingText' => [
                'title' => 'Text to display in content block heading',
                'type'=> 'string',
            ],
            'buttonText' => [
                'title' => 'Text to display in content block button',
                'type'=> 'string',
            ],
            'buttonLink' => [
                'title' => 'Internal URL for content block button',
                'type'=> 'string',
            ],

        ];
    }


    public function init()
    {
        $user = Auth::getUser();
        if (isset($user)) {
            $this->program = $user->currentProgram;
        }
    }

    public function onRun()
    {
        $mediapath = 'https://s3-ap-southeast-2.amazonaws.com/xtendsystem/';
        $content_block = isset($this->program->content_block) ? $this->program->content_block : null;


        $this->background =  isset($this->program->reward_banner_image->path) ? $this->program->reward_banner_image->path : $mediapath.$this->property('imageNamesArray');
        $this->headingText = isset($content_block['reward_banner_title']) ? $content_block['reward_banner_title'] : $this->property('headingText');
        $this->buttonText = isset($content_block['reward_banner_button_text']) ? $content_block['reward_banner_button_text'] : $this->property('buttonText');
        $this->buttonLink = isset($content_block['reward_banner_button_url']) ? $content_block['reward_banner_button_url'] : $this->property('buttonLink');
        $this->btnBorderColor = isset($content_block['reward_banner_button_border_color']) ? $content_block['reward_banner_button_border_color'] : '';
        $this->btn_text_color = isset($content_block['reward_banner_button_text_color']) ? $content_block['reward_banner_button_text_color'] : '';
        $this->titleColor = isset($content_block['reward_banner_title_color']) ? $content_block['reward_banner_title_color'] : '';
        $this->btnBackgroundColor = isset($content_block['reward_banner_button_background_color']) ? $content_block['reward_banner_button_background_color'] : '';

        $this->overrideHoverButton = isset($content_block['reward_banner_overrideHoverButton']) ? $content_block['reward_banner_overrideHoverButton'] : null;
    }

    public function getImageNamesArrayOptions()
    {
        $files = Storage::disk('s3')->files('media/contentblocks');
        foreach($files as $file){
            $array[$file] = $file;
        }
        return $array;
    }
}
