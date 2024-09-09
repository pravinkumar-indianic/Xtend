<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Event;
use Carbon\Carbon;

class ProgramToolsDashboard extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $posts;
    private $moduleEnabled;
    private $manager = false;
    public $html1;

    public function componentDetails()
    {
        return [
            'name' => 'Program Tools Dashboard List',
            'description' => 'Program Tools For Dashboard',
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
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_program_tools'], true);
            if($this->user->currentProgram) {
                $this->manager = $this->user->currentProgram->checkIfManager($this->user);
            }
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
            $this->generateHtml();
    }

    /**
        Fetch the posts from the Current Program
    **/

    public function getPosts($page = 1)
    {

        $this->posts = $this->user->currentProgram->posts()
            ->where('post_type','=','program-tool')
            ->where('status', '=', 'publish')
            ->whereDate('post_live_date','<=',new Carbon);
        if($this->user->currentProgram->use_targeting_tags == true){
            $targetingtagids = $this->user->targetingtags->pluck('id')->toArray();
            $this->posts = $this->posts->whereHas('targetingtags', function($query) use ($targetingtagids){
                $query->whereIn('id', $targetingtagids);
            });
        }
        $this->posts = $this->posts//->limit(4)
            ->orderBy('pinned', 'desc')
            ->orderBy('updated_at','desc')
            ->paginate(3, $page);
    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@dashboardpostslist',
            [
                'user' => $this->user,
                'posts' => $this->posts,
                'manager' => $this->manager,
            ]
        );
    }

    public function onPostsPaginate()
    {
        $this->getPosts(post('page'));
        $html = $this->renderPartial('@programtoolslist.htm',
            [
                'user' => $this->user,
                'posts' => $this->posts,
                'manager' => $this->manager,
            ]
        );

        $result['html']['#program-tools'] = $html;
        return $result;
    }
}

