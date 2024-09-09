<?php namespace Codengine\Awardbank\Components;

use Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Post as MyPost;
use Codengine\Awardbank\models\PostView;
use Rainlab\User\Models\User;
use Codengine\Awardbank\models\Category;
use Codengine\Awardbank\models\Program;
use Codengine\Awardbank\models\Tag;
use Codengine\Awardbank\models\Like;
use Codengine\Awardbank\models\Comment;
use Codengine\Awardbank\Models\Permission;
use Codengine\Awardbank\Models\Message;
use Codengine\Awardbank\Models\Thankyou;
use Auth;
use Input;
use Log;
use DB;
use Redirect;
use Carbon\Carbon;

class PostManagement extends ComponentBase
{
    /** MODELS **/

    public $posts; 
    public $categories;
    public $category;
    public $tags;
    public $post;
    public $messages;
    public $message;
    public $messagetype;

    /** RELATIONSHIP VARS **/

    public $permissions;
    private $entities;
    private $access;
    private $accessRelation;

    /** MODULE DISPLAY VARS **/

    public $moduleHeading;
    public $showHeading;
    public $moduleViewTemplate;    
    public $moduleFAIcon;
    public $moduleFramework;
    public $moduleWidth;
    public $postsPerRow;
    public $maxPosts;
    public $enableComment;
    public $moduleType; 
    public $listMode;

    /** QUERY FILTER VARS **/

    private $categoryinclusions;    
    private $categoryexclusions;
    private $postinclusions;
    private $postexclusions;
    private $sortBy;
    private $sortOrder;
    private $searchTerm;
    private $subCategory;
    private $tagfilter;

    /** HIDE / REVEAL VARS **/

    private $toplevelcategoryonly;
    public $showsearch;
    public $categoryfilter;
    public $pagination;

    // Navigation
    public $steps;
    public $page;
    public $hasmorepage;

    /** USER + USER ENTITY VARS **/
    public $user;
    public $users;
    public $programprimarycolor;
    public $programsecondarycolor;

    public function componentDetails()
    {
        return [
            'name'        => 'postManagement Component',
            'description' => 'Component for post and program management'
        ];
    }

    public function defineProperties()
    {
        return [
            'moduleHeading' => [
                'title'       => 'Module Heading',
                'type'        => 'string',
                'default'     => 'Posts',
            ],   

            'showHeading' => [
                'title' => 'Show Heading',
                'type'=> 'checkbox',
                'default' => true,
            ],

            'moduleViewTemplate' => [
                'title'       => 'Module View Template',
                'type'        => 'dropdown',
                'default'     => 'postlist',
                'options'     => [
                    'postlist' => 'Post List',
                    'postdetail' => 'Post Details',
                    'postcreate' => 'Create New Post',
                ],
            ], 

            'listMode' => [
                'title'       => 'Post list mode',
                'type'        => 'dropdown',
                'default'     => 'loadmore',
                'options'     => [
                    'loadmore' => 'Provice load more button',
                    'paginate' => 'Provide page lists',
                    'autoload' => 'Auto load next post when scroll to bottom',
                ],
            ], 

            'moduleFAIcon' => [
                'title'       => 'Header Icon',
                'type'        => 'string',
                'default'     => ' diamond',
            ],

            'permissions' => [
                'title'       => 'Permissions Type Selection',
                'type'        => 'set',
                'default'     => ['cascade'],
                'placeholder' => 'Select Permissions',
                'items' => [
                    'cascade' => 'cascade',
                    'direct' => 'direct',
                    'ascend' => 'ascend',
                    'owner' => 'owner',
                    'target' => 'target',
                    'winner' => 'winner',
                    'alias' => 'alias',
                    'approver' => 'approver',
                    'manager' => 'manager',
                    'wishlist' => 'wishlist',
                    'toppick' => 'toppick'
                ],
            ],

            'access' => [
                'title'       => 'Permissions Access Relationship',
                'type'        => 'dropdown',
                'default'     => ['user'],
                'placeholder' => 'Select Access Relationship',
                'options'     => [
                    'users'=>'User',
                    'addresss'=>'Address', 
                    'teams'=>'Team',
                    'regions'=>'Region',
                    'programs'=>'Program',
                    'organizations'=>'Organization',                                                            
                ]
            ],

            'entities' => [
                'title'       => 'Permissions Entity Relationship',
                'type'        => 'set',
                'default'     => ['posts'],
                'placeholder' => 'Select Entity Relationship',
                'items'     => [
                    'posts'=>'Posts', 
                    'programs'=>'Programs'
                ]
            ],

            'maxPosts' => [
                 'title'             => 'Max Posts',
                 'description'       => 'The maximum posts per module',
                 'default'           => 5,
                 'type'              => 'string',
                 'validationPattern' => '^[0-9]+$',
                 'validationMessage' => 'The Max Items property can contain only numeric symbols'
            ], 

            'moduleComment' => [
                'title'       => 'Enable or disable comment on module',
                'type'        => 'dropdown',
                'default'     => 'enable',
                'options'     => [
                    'enable' => 'Enable',
                    'disable' => 'Disable'
                ],
            ],
            'moduleGridCols' => [
                'title'             => 'Module Grid Columns',
                'description'       => 'The number of grid columns of products in module',
                'default'           => 12,
                'type'              => 'dropdown',
                'default' => 'Half',
                'options'     => [
                    'four'=>'Quarter',
                    'eight'=>'Half',
                    'twelve'=>'3 Quarter',                    
                    'sixteen'=>'Full',                                                            
                ]
            ],            
        ];
    }

    public function setVars(){

        /** USER BASED VARS**/

        $this->user = Auth::getUser();
        $this->programprimarycolor = $this->user->currentProgram->primary_color;
        $this->programsecondarycolor = $this->user->currentProgram->secondary_color;

        /** RELATIONSHIP VARS **/

        $this->permissions = $this->property('permissions');
        $this->entities = $this->property('entities');
        $this->access = $this->property('access');
        $this->getThisAccessRelation();

        /** MODELS**/

        $this->toplevelcategoryonly = $this->property('toplevelcategoryonly');
        $this->categories = $this->loadCategories();
        $this->tags = $this->loadTags();

        /** MODULE DISPLAY VARS **/

        $this->moduleHeading = $this->property('moduleHeading');
        $this->showHeading = $this->property('showHeading');
        $this->moduleFAIcon = $this->property('moduleFAIcon');
        
        $this->moduleFramework = $this->property('moduleFramework');       
        $this->moduleWidth = $this->property('moduleGridCols');
        $this->postsPerRow = $this->property('postsPerRow');
        $this->maxPosts = $this->property('maxPosts');
        $this->enableComment = $this->property('moduleComment');

        /** QUERY FILTER VARS **/

        $this->categoryinclusions = $this->property('categoryinclusions');           
        $this->categoryexclusions = $this->property('categoryexclusions');
        $this->postinclusions = $this->property('postinclusions');
        $this->postexclusions = $this->property('postexclusions');
        //$this->sortBy = $this->property('sortBy');
        //$this->sortOrder = $this->property('sortOrder');  

        $this->sortBy = 'updated_at';
        $this->sortOrder = 'desc';

        /** HIDE / REVEAL VARS **/

        $this->showsearch = $this->property('showsearch');
        $this->categoryfilter = $this->property('categoryfilter');
        $this->pagination = $this->property('pagination');

        if(in_array('programs', $this->entities)){
            $this->moduleType = "program-tool";
        }elseif(in_array('messages', $this->entities)){
            $this->moduleType = "message";
        }else{
            $this->moduleType = "post";
        }
    }

    public function init()
    {
        
        if ($this->property('moduleViewTemplate') == "postcreate"){

            $component = $this->addComponent(
                'Codengine\Awardbank\Components\ImageUploader',
                'fileUploader',
                ['deferredBinding' => true]
            );

            $component->bindModel('feature_image', new MyPost());

            $component = $this->addComponent(
                'Codengine\Awardbank\Components\ImageUploader',
                'multifileUploader',
                ['deferredBinding' => true]
            );

            $component->bindModel('images', new MyPost());
        }

    }

    public function onRun()
    {

        $this->moduleViewTemplate = $this->property('moduleViewTemplate');
        if ($this->moduleViewTemplate == "notification"){

            // $this->messages = $this->onGetTotalInbox();
            // dd($this->messages);
            //return false;
            $this->addJs('/plugins/codengine/awardbank/assets/js/notif.js');
            return null;
        }
        
        $this->setVars();

        $this->page = 1;
        $this->hasmorepage = false;

        if ($this->moduleViewTemplate == "message") {
            if(null !== $this->param('type')) {
                if ($this->param('type') == 'sent'){
                    
                    $this->messages = $this->loadSentMessages();
                    $this->messagetype = $this->param('type');

                }elseif($this->param('type') == 'inbox'){
                    $this->addJs('/plugins/codengine/awardbank/assets/js/inbox.js');
                    $this->messages = $this->loadInboxMessages();
                    $this->messagetype = $this->param('type');
                    //dd($this->messages);

                }else{
                    $user_id = $this->user->id;
                    $this->message = MyPost::where('id', $this->param('type'))->whereHas('viewer', 
                        function ($query) use ($user_id) {
                            $query->where('user_id', $user_id);
                        }
                    )->first();

                    $this->messagetype = 'inbox';

                    if ($this->message == null){
                        return \Response::make($this->controller->run('404'), 404);
                    }else{
                        $notif = PostView::where('post_id', $this->param('type'))->where('user_id', $user_id)->first();

                        $notif->viewed_at = Carbon::now();
                        $notif->save();

                    }
                    
                }
                

            }

        }elseif ($this->moduleViewTemplate == "newmessage") {

            if(null !== $this->param('post_id')) {
                $user_id = $this->user->id;
                $this->message = MyPost::where('id', $this->param('post_id'))->whereHas('viewer', 
                    function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    }
                )->first();
            }
                    
            $this->users = $this->loadUsers();
            $this->messagetype = 'compose';
                
        }elseif ($this->moduleViewTemplate == "postcreate") {

            $this->steps = 'content';

        }elseif ($this->moduleViewTemplate == "postlistbycategory") {

            $this->category = Category::where('slug', $this->param('category_slug'))->first();

            if ($this->category == null)
                $this->category = Category::where('id', $this->param('category_slug'))->first();

            if ($this->category == null)
                return $this->controller->run('404');

            $this->categories = [$this->category];
            $this->subCategory = [$this->category->id];
            $this->listMode = $this->property('listMode');
            $this->posts = $this->loadPosts();

        }elseif ($this->moduleViewTemplate == "postlist") {
            
            $this->posts = $this->loadPosts();
            $this->listMode = $this->property('listMode');

        }else{

            if(null !== $this->param('post_id')) {

                $this->post = MyPost::whereIn('post_type', ['post', 'program-tool'])->where('slug', $this->param('post_id'))->first();

                if ($this->post == null)
                    $this->post = MyPost::whereIn('post_type', ['post', 'program-tool'])->where('id', $this->param('post_id'))->first();

                if ($this->post == null)
                    return $this->controller->run('404');
                
            } else {
                
                return $this->controller->run('404');

            }
        }

    }

    protected function loadPosts()
    {

        $return = [];

        $permissions = null;

        $posts = null;

        if(in_array('posts', $this->entities)){

            $type = 'post';            

            $posts = MyPost::where('post_type','post');

        }

        if(in_array('programs', $this->entities)){

            $type = 'program-tool';            

            $posts = MyPost::where('post_type','program-tool');

        }
        
        if($posts != null){

            $posts = $this->postQueryFilters($posts, $type);
            $offset = ($this->page * $this->maxPosts) - $this->maxPosts;
            $total = $posts->count();

            $posts = $posts->orderBy($this->sortBy, $this->sortOrder)->offset($offset)->limit($this->maxPosts)->get(); 

            $posts = new \Illuminate\Pagination\LengthAwarePaginator($posts, $total, $this->maxPosts, $this->page);

            //dump($posts->orderBy($this->sortBy, $this->sortOrder)->distinct()->paginate($this->maxPosts, $this->page)->total());die();
            if($posts->lastPage() > $this->page)
                $this->hasmorepage = true;

            $return = $posts;

        } else {

        }

        return $return;

    }

    public function postQueryFilters($posts , $type){

        $posts = $posts->whereHas('viewability', function($query){
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
        });

        if($this->user->currentProgram->use_targeting_tags == true){

            $targetingtagids = $this->user->targetingtags->pluck('id')->toArray();

            $posts = $posts->whereHas('targetingtags', function($query) use ($targetingtagids){
                $query->whereIn('id', $targetingtagids);
            });
        }

        if($this->subCategory){

            $posts = $posts->whereHas('categories', function($query){
                $query->whereIn('category_id', $this->subCategory);
            });
        }

        if($this->tagfilter){
            $posts = $posts->whereHas('tags', function($query){
                $query->whereIn('tag_id', $this->tagfilter);
            });
        }

        if($this->searchTerm){

            $posts->where('title', 'like', '%' . $this->searchTerm . '%');

        }

        return $posts;      

    }

    protected function loadCategories($withProducts = false)
    {

        /** NEEDS ALL PERMISSION ATTACHMENT - CURRENTLY ASSUMES PROGRAM **/

        $result = Category::where('type','=','post')->whereHas('viewability', function($query){

            $query->whereHas('programs',function($query){

                $query->where('codengine_awardbank_programs.id', $this->user->teams()->first()->regions()->first()->program->id);
            });

            /**NEED THE CATEGORIES RELATION TO GO HERE**/

        });

        if($withProducts == true){
            $result->whereHas('post');
        }

        if($this->toplevelcategoryonly == true){
            $result->where('parent_id','=',null);
        }

        $result = $result->get();

        return $result;
    }

    protected function loadTags()
    {
        /** NEEDS ALL PERMISSION ATTACHMENT - CURRENTLY ASSUMES PROGRAM **/

        $result = Tag::whereHas('viewability', function($query) {

            $query->whereHas('programs',function($query){

                $query->where('codengine_awardbank_programs.id', $this->user->teams()->first()->regions()->first()->program->id);
            });

        })->get();
        
        return $result;
    }

    protected function loadUsers()
    {

        $result = User::whereHas('teams.regions.program.organization', function($query) {
                    $query->where('id', $this->user->currentProgram->organization->id);
                })->where('id', '!=',$this->user->id)->orderBY('id', 'desc')->get();

        return $result;

    }

    public function onLoadMoreMessage(){
        
        $this->sortBy = 'updated_at';
        $this->sortOrder = 'desc';
        $this->page = post('page');
        $this->messages = $this->loadInboxMessages();
    }

    protected function loadInboxMessages(){
        // $return = array();

        // $permissions = $this->user->permissions()->IsActive()->InEntity($this->permissions)->whereHas('posts', function ($query) {
        //             $query->where('post_type','message');
        //         })->orderBY('created_at', 'desc')->get();
        //     //dd($permissions);
        // foreach($permissions as $permission){
            
        //     $posts = $permission->posts()->get();
        //     foreach($posts as $post ){  
        //         array_push($return, $post);
        //     }
        // }
        // return $return;

        $messages = null;

        $this->user = Auth::getUser();

        $messages = Message::where('receiver_id', $this->user->id)->get();
        $thankyous = Thankyou::where('receiver_id', $this->user->id)->get();

        $collection = collect();

        foreach($messages as $message){

            $object =  (object) array();
            $object->type = 'Message';
            $object->created_at = $message->created_at;
            $object->sender_fullname = $message->sender_fullname;
            $object->sender_id = $message->sender_id;
            $object->message_text = $message->message_text;
            $collection->push($object);

        }

        foreach($thankyous as $thankyou){

            $object =  (object) array();
            $object->type = 'Thankyou';
            $object->created_at = $thankyou->created_at;
            $object->sender_fullname = $thankyou->sender_fullname;
            $object->sender_id = $thankyou->sender_id;
            $object->message_text = $thankyou->thankyou_text;
            $collection->push($object);
        }

        return $collection->sortBy('created_at')->reverse();

    }

    public function onGetMessage(){

        $auth = Auth::getUser();
        $notif = PostView::where('post_id', post('postId'))->where('user_id', $auth->id)->first();

        if ( $notif != null){
            $notif->viewed_at = Carbon::now();
            $notif->save();
        }
        
        $this->message = MyPost::find(post('postId'));

        $result['html'] = $this->renderPartial('@sharedinbox',
            [
                'message' => $this->message
            ]
        );

        return $result;
    }

    protected function loadSentMessages(){
        $messages = null;

        $this->user = Auth::getUser();

        $messages = Message::where('sender_id', $this->user->id)->get();
        $thankyous = Thankyou::where('sender_id', $this->user->id)->get();

        $collection = collect();

        foreach($messages as $message){

            $object =  (object) array();
            $object->type = 'Message';
            $object->created_at = $message->created_at;
            $object->sender_fullname = $message->sender_fullname;
            $object->sender_id = $message->sender_id;
            $object->message_text = $message->message_text;
            $collection->push($object);

        }

        foreach($thankyous as $thankyou){

            $object =  (object) array();
            $object->type = 'Thankyou';
            $object->created_at = $thankyou->created_at;
            $object->sender_fullname = $thankyou->sender_fullname;
            $object->sender_id = $thankyou->sender_id;
            $object->message_text = $thankyou->thankyou_text;
            $collection->push($object);
        }

        return $collection->sortBy('created_at')->reverse();
    }

    public function getThisAccessRelation(){

        /**CREATE BASE QUERY FOR ACCESS LEVEL FROM USER SESSION**/

        if($this->access == 'organizations'){

            /**WILL NEED TO PASS A PROGRAM SESSION IN HERE IN PLACE OF FIRST**/

            $this->accessRelation = $this->user->currentProgram->organization;

        } elseif ($this->access == 'programs') {

            /**WILL NEED TO PASS A PROGRAM SESSION IN HERE IN PLACE OF FIRST**/

            $this->accessRelation = $this->user->currentProgram;

        /** }  elseif ($this->access == 'regions') {

        

            $this->accessRelation = $this->user->teams->first()->regions->first();
            **/

        } elseif ($this->access == 'teams'){

            $this->accessRelation = $this->user->teams->first();

        } else {

            $this->accessRelation = $this->user;    
                    
        }

    }

    /** RETURN ALL AVAILABLE CATEGORIES TO THE CATEGORIES FILTER**/

    public function getCategoryexclusionsOptions()
    {

        $categories = Category::isPost()->get();

        foreach($categories as $category){

            $array[$category->name] = $category->name;

        }

        return $array;
    } 

    /** CREATE NEW POST **/
    public function onPostCreate(){

        $this->setVars();

        $post_id = post('post_id');
        $post = MyPost::find($post_id);
        $step = post('step');
        
        if ( $post == null){
            if ($step == 'content'){
                $post = new MyPost();
            }else{
                return Redirect::to('/' . $this->moduleType . '/create');
            }
        }

        if ($step == 'content'){
            $post->title = post('title');
            $post->content = post('content');
            $post->post_type = post('type');

            $post->save();

            /** Add permission access as Product OWNER **/
            $permission = Permission::create([
                'type'                => 'owner',
                'active'                => 1,
            ]);
            $permission->posts()->attach($post->id);
            $permission->users()->attach($this->user->id);

            $this->steps = 'config';
            //$this->categories = $this->loadCategories();
            //$this->tags = $this->loadTags();  

        }elseif($step == 'config'){
            /** Check for post to and post as to create permissions **/
            $postas = post('postas');
            $postasid = post('postasname');
            $postto = post('postto');
            $posttoids = post('posttoname');

            if(post('category')){
                foreach (post('category') as $cat) {
                    $post->categories()->attach($cat);
                }
            }

            /** Add permission access as POST TARGET **/
            $permission = Permission::create([
                'type'                => 'cascade',
                'active'                => 1,
            ]);
            $permission->posts()->attach($post->id);

            foreach ($posttoids as $posttoid) {
                if ($postto == 'team'){
                    $permission->teams()->attach($posttoid);
                }elseif ($postto == 'program'){
                    $permission->programs()->attach($posttoid);
                }elseif ($postto == 'region'){
                    $permission->regions()->attach($posttoid);
                }elseif ($postto == 'organization'){
                    $permission->organizations()->attach($posttoid);
                }else{
                    $permission->users()->attach($posttoid);
                }
            }

            if (post('tags') != ""){
                $tags = explode(",", post('tags'));

                foreach ($tags as $tag) {
                    $thetag = Tag::findByName($tag)->first();

                    if ($thetag != null){
                        $post->tags()->attach($thetag->id);
                        //$permission->tags()->attach($thetag->id);
                    }else{
                        $thetag = new Tag();
                        $thetag->name = $tag;
                        $thetag->save();
                        
                        $post->tags()->attach($thetag->id);
                        //$permission->tags()->attach($thetag->id);
                    }
                }
            }

            /** Add permission access as Product ALIAS **/
            if ($postas == 'team' || $postas == 'other'){
                $permission = Permission::create([
                    'type'                => 'alias',
                    'active'                => 1,
                ]);
                $permission->posts()->attach($post->id);
                
                if ($postas == 'team'){
                    $permission->teams()->attach($postasid);
                }elseif ($postas == 'other'){
                    $permission->users()->attach($postasid);
                }
                
            }

            $post->save();

            $this->steps = 'media';
        }elseif($step == 'media'){
            $post->save(null, post('_session_key'));

            if (post('submit') == "finish"){
                return Redirect::to('/');
            }
        }

        $this->post = $post;

    }

    public function onNewMessageCreate(){

        $auth = Auth::getUser();

        foreach(post('posttoname') as$value){

            $message = New Message();
            $receiver = User::find($value);
             $message->receiver_id = $receiver->id;
            $message->receiver_fullname = $receiver->full_name;
            if($receiver->avatar){
                $message->receiver_thumb_path = $receiver->avatar->getThumb(100,100, ['mode' => 'crop']);
            } else {
                $message->receiver_thumb_path = null;
            }
        $sender = $auth;
        $message->sender_id = $sender->id;
        $message->sender_fullname = $sender->full_name;
        if($sender->avatar){
            $message->sender_thumb_path = $sender->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $message->sender_thumb_path = null;
        }
        $message->message_text = post('content');

        $program = Program::find($sender->current_program_id);
        $message->program_id = $program->id;
        $message->program_name = $program->name;
        $message->program_points_name = $program->points_name;
        $message->program_points_multiple_type = $program->program_markup_type;
        $message->program_points_multiple_integer = $program->scale_points_by;
        if ($program->login_image){
            $message->program_image_path = $program->login_image->path;
        } else {
            $message->program_image_path = null;
        }

        $message->save();

        }

        return Redirect::to('/message/inbox');
    }

    /** ON POST AS DROPDOWN CHANGE **/
    public function onPostasSelect()
    {
        $access = post('type');

        $user = Auth::getUser();

        if ($access == 'team'){
            $users = $user->teams->all();
        }else{
            $teams = $user->teams->all();
            $users = array();
            foreach ($teams as $key => $value) {
                foreach ($value->users as $key => $user) {
                    array_push($users, $user);
                }
            }
        }
        
        return $users;
    }

    /** ON POST TO DROPDOWN CHANGE **/
    public function onSelectPostTo()
    {
        $access = post('type');

        $user = Auth::getUser();

        if ($access == 'team'){
            $query = $user->teams->all();
        } elseif ($access == 'program') {
            $programid = $user->teams->first()->regions->first()->program_id;
            $query[] = $user->teams->first()->regions->first()->program->find($programid);
        }elseif ($access == 'region'){
            $query[] = $user->teams->first()->regions->first();
        }elseif ($access == 'organization'){
            $query[] = $user->teams->first()->regions->first()->program->first()->organization->first();
        }else{
            $teams = $user->teams->all();
            $query = array();
            foreach ($teams as $key => $value) {
                foreach ($value->users as $key => $user) {
                    array_push($query, $user);
                }
            }
        }
        
        return $query;
    }

    /** UPDATE POST LIKE **/

    public function onLikeClick(){

        $post_id = post('post_id');
        $post = null;

        $post = MyPost::find($post_id);

        $auth = Auth::getUser();

        $like = Like::LikeBy($auth->id)->first();
        if ($like == null){
            $like = Like::create([
                'user_id'  => $auth->id
            ]);
        }
        
        if ($post->IsLiked)
            $post->like()->detach($like->id);
        else
            $post->like()->attach($like->id);

        $post->load('like');

        $this->post = $post;
    }

    public function onRefreshListFilter(){

        $this->setVars();
        
        $this->page = post('page');

        if (post('sortBy') == "asc" || post('sortBy') == "desc"){
            $this->sortBy = 'title';
            $this->sortOrder = post('sortBy');
        }else if (post('sortBy') == "new"){
            $this->sortBy = 'updated_at';
            $this->sortOrder = 'desc';
        }else{
            $this->sortBy = 'updated_at';
            $this->sortOrder = 'asc';
        }
        
        $this->subCategory = post('subCategory');
        $this->searchTerm = post('searchTerm');
        $this->tagfilter = post('tagfilter');
        
        //Log::info(post());

        $this->posts = $this->loadPosts();

        $result['html'] = $this->renderPartial('@sharedpostlist',
            [
                'posts' => $this->posts,
                'page' => $this->page,
                'hasmorepage' => $this->hasmorepage,
                'totalpage' => $this->posts->total()/$this->posts->perPage(),
                'listMode' => $this->property('listMode')
            ]
        );

        $result['page'] = $this->page;

        return $result;
    }

    public function onLoadMoreComment(){
        $post_id = post('post_id');
        $last_id = post('last');

        $post = null;

        $post = MyPost::find($post_id);

        $comments = $post->comment()->LatestComment($lastcomment_id)->get();

        return $comments;
    }

    public function onAddComment(){
        $post_id = post('post_id');
        $comment = post('comment');
        $post = null;

        $auth = Auth::getUser();
        
        $post = MyPost::find($post_id);

        if ($comment != "" || $comment != null){
            $comment = Comment::create([
                'comment'  => $comment,
                'user_id'  => $auth->id
            ]);

            $post->comment()->attach($comment->id);
            $post->save();
        }

        $this->post = $post;

    }

}
