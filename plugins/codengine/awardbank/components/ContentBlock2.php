<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Illuminate\Support\Facades\Log;

class ContentBlock2 extends ComponentBase
{

    /** MODELS **/

    public $lhsimage;
    public $lhsHeadingIcon;
    public $lhsHeadingText;
    public $lhsLine1;
    public $lhsLine2;
    public $lhsLink;
    public $rhsimage;
    public $rhsLine1;
    public $rhsLine2;
    public $rhsLink;
    public $program;


    public function componentDetails()
    {
        return [
            'name' => 'Content Block 2',
            'description' => 'Creates a 2x content blocks horizontally. Left is larger.',
        ];
    }

    public function defineProperties()
    {
        return [
            'lhsImageNamesArray' => [
                'title'             => 'Select LHS Image',
                'type'              => 'dropdown',
            ],
            'lhsHeadingIcon' => [
                'title' => 'Icon to display in LHS heading',
                'type'=> 'string',
            ],
            'lhsHeadingText' => [
                'title' => 'Text to display in LHS heading',
                'type'=> 'string',
            ],
            'lhsLine1' => [
                'title' => 'Text to display in LHS Line 1',
                'type'=> 'string',
            ],
            'lhsLine2' => [
                'title' => 'Text to display in LHS Line 2',
                'type'=> 'string',
            ],
            'lhsLink' => [
                'title' => 'Link for LHS box',
                'type'=> 'string',
            ],
            'rhsImageNamesArray' => [
                'title'             => 'Select RHS Image',
                'type'              => 'dropdown',
            ],
            'rhsLine1' => [
                'title' => 'Text to display in RHS Line 1',
                'type'=> 'string',
            ],
            'rhsline2' => [
                'title' => 'Text to display in RHS Line 2',
                'type'=> 'string',
            ],
            'rhsLink' => [
                'title' => 'Link for RHS box',
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
        $mediapath = 'https://s3-ap-southeast-2.amazonaws.com/xtendsystem';

        if (
            isset($this->program->dashboard_image_first) &&
            isset($this->program->dashboard_image_first->path) &&
            !empty($this->program->dashboard_image_first->path)
        ) {
            $this->lhsimage = $this->program->dashboard_image_first->path;
        } else {
            $this->lhsimage =  $mediapath . '/' . $this->property('lhsImageNamesArray');
        }


        $this->lhsHeadingIcon = $this->property('lhsHeadingIcon');
        $this->lhsHeadingText = $this->property('lhsHeadingText');
        $this->lhsLine1 = $this->property('lhsLine1');
        $this->lhsLine2 = $this->property('lhsLine2');
        $this->lhsLink = $this->property('lhsLink');




        if (
            isset($this->program->dashboard_image_second) &&
            isset($this->program->dashboard_image_second->path) &&
            !empty($this->program->dashboard_image_second->path)
        ) {
            $this->rhsimage = $this->program->dashboard_image_second->path;
        } else {
            $this->rhsimage =  $mediapath . '/' . $this->property('rhsImageNamesArray');
        }




        $this->rhsLine1 = $this->property('rhsLine1');
        $this->rhsLine2 = $this->property('rhsLine2');
        $this->rhsLink = $this->property('rhsLink');
    }

    public function getLhsImageNamesArrayOptions()
    {
        $files = Storage::disk('s3')->files('media/contentblocks');
        foreach($files as $file){
            $array[$file] = $file;
        }
        return $array;
    }

    public function getRhsImageNamesArrayOptions()
    {
        $files = Storage::disk('s3')->files('media/contentblocks');
        foreach($files as $file){
            $array[$file] = $file;
        }
        return $array;
    }
}
