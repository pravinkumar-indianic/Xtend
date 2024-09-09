<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Post;
use Auth;
use Event;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Request;
use Redirect;

class PostEdit extends ComponentBase
{
    /** MODELS **/

    private $user;
    private $imagecomponent;
    private $ckeditorfactorycomponent;
    private $moduleEnabled;
    private $navoptions = [];
    private $navoption;
    private $post;
    private $posttype = 'post';
    public $new = false;
    public $html1;
    public $html2;

    public function componentDetails()
    {
        return [
            'name'        => 'Post Edit',
            'description' => 'Post Edit'
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
            $this->user =  $this->user->load('currentProgram');
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_posts'], true);
            $this->getModels();
        }

        if (Request::has('posttype')) {
            $this->posttype = Request::get('posttype');
        }

        $this->ckeditorfactorycomponent = $this->addComponent(
            'Codengine\Awardbank\Components\CKEditorFactory',
            'CKEditorFactory',
            [
                'deferredBinding' => false
            ]
        );
    }

    public function onRun(){
        if($this->moduleEnabled == true){
            $this->addJs('/plugins/codengine/awardbank/assets/js/PostEdit171019.js');
            if($this->navoption == null){
                $this->navoption = $this->param('navoption');
            }
            $this->imagecomponent->fileList = $this->post->feature_image;
            $this->imagecomponent->singleFile = $this->post->feature_image;
            $this->coreLoadSequence();
        }
    }

    /**
        Component Custom Functions
    **/

    /**
        Reusable function call the core sequence of functions to load and render the Cart partial html
    **/

    public function onRender()
    {
        if (!empty($this->property('posttype'))) {
            $this->posttype = $this->property('posttype');
        }
        $this->coreLoadSequence();
    }

    public function coreLoadSequence()
    {
        $this->setNavOptions();
        $this->factory();
        $this->generateHtml();
    }

    public function setNavOptions()
    {
        if($this->new == false){
            $this->navoptions = [
                'generaldetails' => 'General Details',
                'images' => 'Images',
            ];
            if(array_key_exists($this->navoption, $this->navoptions)){
                $this->navoption = $this->navoption;
            } else {
                $this->navoption = 'generaldetails';
            }
        } else {
            $this->navoptions = [
                'generaldetails' => 'General Details',
            ];
            $this->navoption = 'generaldetails';
        }
    }

    public function getModels()
    {
        $slug = $this->param('slug');
        if($slug != 'create'){
            $this->post = Post::where('slug','=', $slug)->first();
        }
        if($this->post == null){
            $this->post = Post::find($slug);
        }
        if($this->post == null || $slug == 'create'){
            $this->post = new Post;
            $this->new = true;
        }
        $this->imagecomponent = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'featureImage',
            [
                'deferredBinding' => false
            ]
        );
        $this->imagecomponent->bindModel('feature_image', $this->post);
    }

    public function factory()
    {
        $this->post->program = $this->user->currentProgram;
    }

    /**%}
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@postlhsnav',
            [
                'navoptions' => $this->navoptions,
                'navoption' => $this->navoption,
                'post' => $this->post,
            ]
        );
        $this->html2 = $this->renderPartial('@'.$this->navoption,
            [
                'post' => $this->post,
                'new' => $this->new,
                'posttype' => $this->new ? $this->posttype : $this->post->post_type,
            ]
        );
    }

    public function testPost($input)
    {
        if($input !== '' && !empty($input)){
            return true;
        } else {
            return false;
        }
    }

    /**
        AJAX Requests
    **/

    public function onUpdateTab()
    {
        if($this->testPost(post('navoption')) == true){
            $this->navoption = post('navoption');
            if($this->navoption  == 'images'){
                $result['fileuploaderrun'] = 1;
            }
        }
        $this->pageCycle();
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        return $result;
    }

    public function onUpdateGeneralDetails()
    {
        try{
            $this->pageCycle();
            if($this->post){
                $this->post->status = post('status') ?? 'publish';
                $this->post->post_type = post('Post_Type');
                if($this->testPost(post('Title')) == true){
                    $this->post->title = post('Title');
                }
                if($this->testPost(post('Content')) == true){
                    $this->post->content = post('Content');
                }
                $this->post->pinned = (int)(post('pinned') ?? 0);
                if($this->testPost(post('Post_Live_From')) == true){
                    $this->post->post_live_date = post('Post_Live_From');
                }
                unset($this->post->program);
                $this->post->save();
                if($this->new == true){
                    $this->post->programs()->add($this->user->currentProgram);
                    $this->post->poster_id = $this->user->id;
                    $this->post->managers()->sync([$this->user->id]);
                } else {
                    if($this->testPost(post('Managers')) == true){
                        $array = explode(",",post('Managers'));
                        $this->post->managers()->sync($array);
                    } else{
                        $this->post->managers()->sync([$this->user->id]);
                    }
                }
                $this->post->save();
            }
            if($this->new == true){
                return Redirect::to('/post/'.$this->post->slug.'/edit');
            } else {
                $this->navoption = 'generaldetails';
                $this->pageCycle();
                $result['updatesucess'] = "Post updated.";
                $result['html']['#html1target'] = $this->html1;
                $result['html']['#html2target'] = $this->html2;
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}
