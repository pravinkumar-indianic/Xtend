<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Event;

class FooterUsefullInfo extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $posts;
    private $moduleEnabled;
    private $footer1;
    private $footer2;
    public $html1;
    public $html2;

    public function componentDetails()
    {
        return [
            'name' => 'Footer Usefull Info Module',
            'description' => 'Footer Usefull Info Module',
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
            $this->user = $this->user->load('currentProgram','currentProgram.posts');
            $this->pointsname = $this->user->currentProgram ? $this->user->currentProgram->points_name : false;
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_posts'], true);
        }
    }

    public function onRun()
    {
        if($this->moduleEnabled == true){
            $this->coreLoadSequence();
        }
    }

    /**
        Run the core functions for processing variables and generating html.
    **/

    public function coreLoadSequence()
    {
        $this->getPosts();
        $this->buildHtml();
    }

    public function getPosts()
    {
        $this->footer1 = $this->user->currentProgram->posts()->where('post_type','=','useful-information');
        if($this->user->currentProgram->use_targeting_tags == true){
            $targetingtagids = $this->user->targetingtags->pluck('id')->toArray();
            $this->footer1 = $this->footer1->whereHas('targetingtags', function($query) use ($targetingtagids){
                $query->whereIn('id', $targetingtagids);
            });
        }
        $this->footer1 = $this->footer1
        ->limit(8)
        ->orderBy('codengine_awardbank_posts.updated_at','desc')
        ->get();

        $this->footer2 = $this->user->currentProgram->posts()->where('post_type','=','useful-information');
        if($this->user->currentProgram->use_targeting_tags == true){
            $targetingtagids = $this->user->targetingtags->pluck('id')->toArray();
            $this->footer2 = $this->footer2->whereHas('targetingtags', function($query) use ($targetingtagids){
                $query->whereIn('id', $targetingtagids);
            });
        }
        $this->footer2 = $this->footer2
        ->offset(8)
        ->limit(8)
        ->orderBy('codengine_awardbank_posts.updated_at','desc')
        ->get();
    }

    public function buildHtml()
    {
        $this->html1 = $this->renderPartial('@footerinfo',
            [
                'options' => $this->footer1,
            ]
        );
        $this->html2 = $this->renderPartial('@footerinfo',
            [
                'options' => $this->footer2,
            ]
        );
    }

}
