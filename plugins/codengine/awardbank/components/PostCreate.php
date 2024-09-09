<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Post;
use Codengine\Awardbank\Models\Category;
use Codengine\Awardbank\Models\Tag;
use Codengine\Awardbank\Models\Organization;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Permission;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;

class PostCreate extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $container;
    public $flushtop;
    public $flushbottom;
    public $categories;
    public $tags;
    public $targetOptions;


    public function componentDetails()
    {
        return [
            'name' => 'Post Create',
            'description' => 'Create A Post',
        ];
    }

    public function defineProperties()
    {
        return [
            'container' => [
                'title' => 'Wrap Component In The Container',
                'type'=> 'checkbox',
                'default' => false,
            ],   
            'flushtop' => [
                'title' => 'Remove Top Padding',
                'type'=> 'checkbox',
                'default' => false,
            ], 
            'flushbottom' => [
                'title' => 'Remove Bottom Padding',
                'type'=> 'checkbox',
                'default' => false,
            ], 
        ];
    }


    public function init()
    {

        $this->user = Auth::getUser();

        $component = $this->addComponent(
            'Codengine\Awardbank\Components\ImageUploader',
            'fileUploader',
            ['deferredBinding' => true]
        );

        $component->bindModel('feature_image', new Post);

        $component = $this->addComponent(
            'Codengine\Awardbank\Components\ImageUploader',
            'multifileUploader',
            ['deferredBinding' => true]
        );

        $component->bindModel('images', new Post);

    }

    public function onRun()
    {

        $this->container = $this->property('container');
        $this->flushtop = $this->property('flushtop');
        $this->flushbottom = $this->property('flushbottom');
        $this->categories = Category::where('type','=','post')
            ->whereHas('viewability', function($query){
                $query->whereHas('organizations', function($query){
                    $query->where('codengine_awardbank_organizations.id','=',$this->user->current_org_id);
                })->orWhereHas('programs', function($query){
                    $query->where('codengine_awardbank_programs.id','=',$this->user->current_program_id);
                })->orWhereHas('regions', function($query){
                    $query->where('codengine_awardbank_regions.id','=',$this->user->current_region_id);
                })->orWhereHas('teams', function($query){
                    $query->where('codengine_awardbank_teams.id','=',$this->user->current_team_id);
                })->orWhereHas('users', function($query){
                    $query->where('users.id','=',$this->user->id);
                });
            })
        ->get();
        $this->tags = Tag::all();
        $this->targetOptions = $this->user->getTargetArray();

    }

    public function onPostCreateContent(){

        $title = post('title');
        $content = post('content');
        $video = post('video');
        $postto = post('postto');
        $posttoname = post('posttoname');
        $postas = post('postas');
        $postasname = post('postasname');
        $categories = post('category');
        $posttype = post('posttype');
        $tags = post('tags');
        $tags = explode(',',$tags);
        $viewabilitypermissions = post('postviewability');
        $aliaspermissions = post('postalias');

        $post = new Post;
        $post->title = $title;
        $post->content = $content;
        $post->post_type = $posttype;
        $post->video_url = post('video');
        $post->save(null, post('_session_key'));

        $permissions = [];

        foreach($viewabilitypermissions as $viewability){

            $viewability = explode('=>', $viewability);
            $permissions['viewability'][] = $this->createAPermission('cascade',$viewability[0],$viewability[1]);

        }

        if(is_array($aliaspermissions)){
            foreach($aliaspermissions as $viewability){
                $viewability = explode('=>', $viewability);
                if(is_array($viewability) && sizeof($viewability) == 2){
                    $permissions['alias'][] = $this->createAPermission('alias',$viewability[0],$viewability[1]);
                }
            }
        }

        if(isset($permissions['viewability'])){
            foreach($permissions['viewability'] as $permission ){
                $post->viewability()->add($permission);
            }
        }

        if(isset($permissions['alias'])){
            foreach($permissions['alias'] as $permission ){
                $post->alias()->add($permission);
            }
        }

        $permission = new Permission();
        $permission->type = "owner";
        $permission->save();
        $permission->posts()->add($post);

        $user = Auth::getUser();
        $permission->users()->add($user);

        if(!empty($categories)){
            foreach($categories as $category){

                $newCategory = Category::find($category);
                $post->categories()->add($newCategory);            

            }
        }

        if(!empty($tags)){
            foreach($tags as $tag){

                $newTag = Tag::where('name','=',$tag)->first();

                if($newTag == null){
                    $newTag = new Tag;
                    $newTag->name = $tag;
                    $newTag->save();
                }

                $post->tags()->add($newTag);            

            }
        }

        $result['id'] = $post->id;
        $result['slug'] = $post->slug;
        return $result;

    }
    
    public function createAPermission($type,$access_type,$access_id){
        $access_id = (int)$access_id;
        $access_type = trim($access_type);
        $permission = new Permission;
        $permission->type = $type;
        $permission->save();
        $entity = null;
        if($access_type == 'organizations'){
            $entity = Organization::find($access_id);        
        } elseif($access_type == 'programs'){
            $entity = Program::find($access_id);
        } else if ($access_type == 'regions'){
            $entity = Region::find($access_id);
        } else if ($access_type == 'teams'){
            $entity = Team::find($access_id);
        } else if ($access_type == 'users'){
            $entity = User::find($access_id);
        }
        $permission->$access_type()->add($entity);

        return $permission;
    }

}
