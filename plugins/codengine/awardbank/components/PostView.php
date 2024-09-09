<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Post;
use Codengine\Awardbank\Models\Comment;
use Codengine\Awardbank\Models\Like;
use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Traits\OembedRenderer;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;
use Event;

class PostView extends ComponentBase
{
    use OembedRenderer;

    /** MODELS **/
    private $user;
    private $post;
    private $moduleEnabled;
    public $html1;

    public function componentDetails()
    {
        return [
            'name' => 'Post View',
            'description' => 'View A Post',
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
            $this->user = $this->user->load('currentProgram');
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_posts'], true);
            if($this->moduleEnabled == false){
                $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_program_tools'], true);
            }
        }

    }

    public function onRun()
    {
        if($this->moduleEnabled){
            $this->addJs('/plugins/codengine/awardbank/assets/js/PostView110919.js');
            $this->coreLoadSequence();
        }
    }

    public function coreLoadSequence()
    {
        $this->getPost();
        $this->postFactory();
        $this->generateHtml();
    }

    public function getPost()
    {
        $slug = $this->param('slug');
        $this->post = Post::where('slug','=', $slug)->first();
        if($this->post == null){
            $this->post = Post::find($slug);
        }
        if($this->post){
            $this->post = $this->post->load('categories','images','feature_image','files','tags','poster','alias','managers','teams','programs','targetingtags','comments','likes');
        }
    }

    public function postFactory()
    {
        $this->post->managed = $this->user->currentProgram->checkIfManager($this->user);
        $managerusers = $this->post->managers()->pluck('id')->toArray();
        if(in_array($this->user->id,$managerusers)){
            $this->post->managed = true;
        }

        if(!$this->post->managed) {
            $this->post->managed = $this->checkIfSymbionStaff();
        }
    }

    public function generateHtml()
    {
        $post = $this->post;
        $post->content = self::renderOembedMulti($post->content);

        $this->html1 = $this->renderPartial('@postview',
            [
                'post' => $post,
            ]
        );
    }

    public function onLoadMoreComment(){

        $post_id = post('post_id');

        $last_id = post('last');

        $post = null;

        $post = Post::find($post_id);

        $comments = $post->comments()->LatestComment($lastcomment_id)->get();

        return $comments;
    }

    public function onCreateComment(){
        $this->getPost();
        $comment = post('Comment');
        $comment = Comment::create([
            'comment'  => $comment,
            'user_id'  => $this->user->id
        ]);
        $this->post->comments()->add($comment);
        $this->coreLoadSequence();
        $result['updatesucess'] = "Comment created.";
        $result['html']['#html1target'] = $this->html1;
        return $result;
    }

    public function onDeleteComment(){
        $comment_id = post('comment_id');
        $comment = Comment::find($comment_id);
        $this->post->comment()->remove($comment);
        $comment->delete();
        $result['commentlist'] = $this->renderPartial('@commentlist');
        $result['postlike'] = $this->renderPartial('@postlike');
        return $result;
    }

    public function onUpdateLike(){
        $this->getPost();
        $like = $this->post->likes()->where('user_id', $this->user->id)->first();
        $new = false;
        if($like == null){
            $like = Like::create([
                'user_id'  => $this->user->id
            ]);
            $new = true;
        }
        if($new == false){
            $this->post->likes()->detach($like->id);
        } else{
            $this->post->likes()->attach($like->id);
        }
        $this->coreLoadSequence();
        $result['updatesucess'] = "Endorsement updated.";
        $result['html']['#html1target'] = $this->html1;
        return $result;

    }

    private function checkIfSymbionStaff() {
        if (!empty($this->user->roll) && !empty($this->user->currentProgram)) {
            if ($this->user->roll == 'Symbion Staff' && $this->user->currentProgram->name == '5STEP Incentive') {
                return true;
            }
        }

        return false;
    }
}
