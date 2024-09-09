<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Award;
use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Category;
use Codengine\Awardbank\Models\Post;
use October\Rain\Exception\AjaxException;
use Storage;
use Config;
use Auth;
use Event;
use Carbon\Carbon;

class PostsList extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $posts;
    private $manager = false;
    private $moduleEnabled;
    private $categories;
    public $html1;
    public $header = true;

    public function componentDetails()
    {
        return [
            'name' => 'Posts List',
            'description' => 'List Of All Posts',
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
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_posts'], true);
            if ($this->user->currentProgram) {
                $this->manager = $this->page['manager'] = $this->user->currentProgram->checkIfManager($this->user);
            }
        }
    }

    public function onRun()
    {
        if ($this->moduleEnabled == true){
            $this->coreLoadSequence();
        }
    }

    public function onRender()
    {
        if (!empty($this->property('disableHeader'))) {
            $this->header = false;
        }
        if ($this->moduleEnabled == true){
            $this->coreLoadSequence();
        }
    }

    public function coreLoadSequence()
    {
        $this->getModels();
        $this->factory();
        $this->generateHtml();
    }

    public function getModels(){
        $this->posts = $this->user->currentProgram->posts()
            ->where('post_type','=','post')
            ->whereDate('post_live_date','<=',new Carbon);

        if ($this->user->currentProgram->use_targeting_tags == true) {
            $targetingtagids = $this->user->targetingtags->pluck('id')->toArray();
            $this->posts = $this->posts->whereHas('targetingtags', function($query) use ($targetingtagids){
                $query->whereIn('id', $targetingtagids);
            });
        }
        $this->posts = $this->posts->orderBy('updated_at','desc')->get();
        $this->categories = Category::where('type','=','post')->get();
    }

    public function factory()
    {

    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@postslist',
            [
                'posts' => $this->posts,
                'categories' => $this->categories,
                'tags' => $this->tags,
                'manager' => $this->manager,
                'mode' => 'page'
            ]
        );
    }

    public function onPostDelete()
    {
        if (!empty(post('postId')) && $this->manager) {
            $post = Post::find(post('postId'));
            if ($post) {
                if ($post->delete()) {
                    $result['success'] = true;
                    return $result;
                }
            }
        }

        throw new AjaxException(
            [
                'error' => 'Error while deleting post'
            ]
        );
    }

}
