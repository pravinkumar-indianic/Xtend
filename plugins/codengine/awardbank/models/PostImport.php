<?php 
namespace Codengine\Awardbank\Models;
use Codengine\Awardbank\Models\Post as Post;
use Codengine\Awardbank\Models\Category as Category;
use Codengine\Awardbank\Models\Tag as Tag;
use RainLab\User\Models\User as User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \System\Models\File as File;
use BackendAuth;
use Db;
use Storage;
use Session;

class PostImport extends \Codengine\Awardbank\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */

    protected $fillable = [
        'import_supplier',
        'owner_override',
        'owner_permission_access',
        'owner_user_permission_id',
        'owner_team_permission_id',
        'owner_region_permission_id',
        'owner_program_permission_id',
        'owner_organization_permission_id',
        'viewability_override',
        'viewability_permission_access',
        'viewability_permission_type',
        'viewability_user_permission_id',
        'viewability_team_permission_id',
        'viewability_region_permission_id',
        'viewability_program_permission_id',
        'viewability_organization_permission_id',
        'category_type',
        'category_override',
        'tag_type',
        'tag_override',
    ];

    public $rules = [];

    public $table = 'canthis_importexport_import_log';

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'author' => ['Backend\Models\User']
    ];
    
    public $attachOne = [
        'import_file' => ['System\Models\File']
    ];
    
    /**
     * Adds this import to products import log table
     * @param type $sessionKey
     */
    public function importLog($sessionKey = null) {   

    }    

    protected $resultStats = [
        'processed' => 0, // Added this for overall progress percentage calculation
        'updated' => 0,
        'created' => 0,
        'errors' => [],
        'warnings' => [],
        'skipped' => []
    ];     

    public function importData($results, $sessionKey = null)
    {

        foreach ($results as $row => $data) {

            try {

                /**
                    GET EXISTING PRODUCT IF ID COLUMN EXISTS AND PRODUCT WITH THAT ID EXISTS.
                    OTHERWISE CREATE NEW PRODUCT.

                **/

                $feature_image = null;
                $update = false;

                if(isset($data['id']) && !empty($data['id'])){

                    $post = Post::find($data['id']);

                    if($post == null){

                        $post = new Post;

                    } else {

                        $update = true;
                    }

                } else {

                    $post = new Post;

                }

                if(isset($data['title']) && !empty($data['title'])){
                    $post->title = $data['title'];
                }
 
                if(isset($data['content']) && !empty($data['content'])){

                    $content = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $data['content']));
                    $content = htmlentities($content);               
                    $post->content = $content;  

                } elseif($post->content == null || !isset($data['content']) || empty($data['content'])){
                    $post->content = '';                  
                }

                if(isset($data['post_type']) && !empty($data['post_type'])){
                    $post->post_type = $data['post_type'];
                }
                
                if(isset($data['video_url']) && !empty($data['video_url'])){
                    $post->video_url = $data['video_url'];
                }

                if(isset($data['viewed_at']) && !empty($data['viewed_at'])){
                    $post->viewed_at = $data['viewed_at'];
                }


                if(isset($data['feature_image']) && !empty($data['feature_image'])){
                    if($target = File::where('file_name','=', $data['feature_image'])->first()){

                        $feature_image = new File;
                        //$feature_image->data = $target->getLocalPath();
                        $feature_image->file_name = $target->file_name;
                        $feature_image->disk_name = $target->disk_name;
                        $feature_image->file_size = $target->file_size;
                        $feature_image->content_type = $target->content_type;
                        $feature_image->is_public = true;
                        $feature_image->save();

                    } else {

                        $directories = Storage::allDirectories('/media/');
                        $directories[] = 'media/';
                        foreach($directories as $path){
                            if(Storage::exists('/'.$path.'/'.$data['feature_image'])){
                                //$feature_image = Storage::get('/media/officeclosure.jpg');
                                $feature_image = new File;
                                $feature_image->data = base_path('storage/app/'.$path.'/'.$data['feature_image']);
                                $feature_image->data;
                                $feature_image->is_public = true;
                                $feature_image->save();
                            }
                        }
                    }

                   //dump($feature_image);
                    //dump(Storage::get('officeclosure.jpg'));
                    
                } else if(isset($data['feature_image']) && empty($data['feature_image'])){

                    $feature_image = 'remove';

                }

                $post->save();

                if($feature_image == 'remove'){ 
                    //echo 'removing';
                    if($post->feature_image){
                        $post->feature_image()->delete();
                    }

                } else if($feature_image != null){
                    $post->feature_image()->add($feature_image);
                } 

                if($update == true){
                    $this->logUpdated();
                } else {
                    $this->logCreated();
                }

                /** PROCESS PERMISSION ARRAY**/

                if((isset($data['my_owner'])) || (is_array($this->owner_permission_access)) || ($this->owner_override == true)){

                    if(!isset($data['my_owner'])){
                        $data['my_owner'] = '';
                    }

                    if($this->owner_override == true){

                        $this->emptyPermissions($post, 'owners');
                        $this->processAccessPermissionsInternal('posts', $post, 'owners');

                    } else {

                        $this->emptyPermissions($post, 'owners');                        
                        $this->processAccessPermissionString($data['my_owner'],'posts', $post, 'owners');
                        $this->processAccessPermissionsInternal('posts', $post, 'owners');                        

                    }
                } 

                if((isset($data['my_view'])) || (is_array($this->viewability_permission_access)) || ($this->viewability_override == true)){

                    if(!isset($data['my_view'])){
                        $data['my_view'] = '';
                    }

                    if($this->viewability_override == true){

                        $this->emptyPermissions($post, 'viewability');
                        $this->processAccessPermissionsInternal('posts', $post, 'viewability');

                    } else {

                        $this->emptyPermissions($post, 'viewability');                        
                        $this->processAccessPermissionString($data['my_view'],'posts', $post, 'viewability');
                        $this->processAccessPermissionsInternal('posts', $post, 'viewability');                        

                    }
                } 

                if((isset($data['my_categories'])) || (is_array($this->category_type)) || ($this->category_override == true)){
                    if($this->category_override == true){
                        if($post->categories){
                            $post->categories()->detach();
                        }
                        $this->processCategoriesOrTagInternal($post, 'categories');
                    } else {
                        if($post->categories){
                            $post->categories()->detach();
                        }
                        $this->processCategoriesOrTagInternal($post, 'categories');
                        if(isset($data['my_categories'])){
                            $dataString = $data['my_categories'];
                        } else {
                            $dataString = '';
                        }                   
                        $this->processCategoriesOrTagString($dataString,'categories', $post);                                           
                    }

                } 

                if((isset($data['my_tags'])) || (is_array($this->tag_type)) || ($this->tag_override == true)){
                    if($this->tag_override == true){
                        if($post->tags){
                            $post->tags()->detach();
                        }
                        $this->processCategoriesOrTagInternal($post, 'tags');
                    } else {
                        if($post->tags){
                            $post->tags()->detach();
                        }
                        $this->processCategoriesOrTagInternal($post, 'tags');    
                        if(isset($data['my_tags'])){
                            $dataString = $data['my_categories'];
                        } else {
                            $dataString = '';
                        }                                                              
                        $this->processCategoriesOrTagString($dataString,'tags', $post);                                           
                    }

                }                 

            }
            catch (\Exception $ex) {
                echo $ex->getMessage();
                $this->logError($row, $ex->getMessage());
            }

        }
    }

    function processCategoriesOrTagInternal($entity,$connection){

        if($connection == 'categories'){
            if(is_array($this->category_type)){
                foreach($this->category_type as $key => $value){
                    $entity->categories()->attach($value);
                }
            }
        } 

        if($connection == 'tags'){
            if(is_array($this->tag_type)){
                foreach($this->tag_type as $key => $value){
                    $entity->tags()->attach($value);
                }
            }
        }         
    }

    function processCategoriesOrTagString($string, $entity_name, $entity){


        /**
            SOFT DELETE OLD PERMISSIONS

        **/

        $datas = (explode(",",$string));

        foreach($datas as $data){

            $id = $this->get_string_between($data,'[',']');
            $id = trim($id);
            $id = intval($id);
            $name = substr($data, strpos($data, "]") + 1);
            $name = trim($name);
            $name = html_entity_decode($name);

            $datamodel = null;

            if($entity_name == 'categories'){
                $datamodel = Category::find($id);    
                if($datamodel == null){
                    $datamodel = Category::where('name','=', $name)->first();                      
                }
                if($datamodel != null){
                    $entity->categories()->attach($datamodel->id);
                }

            }

            if($entity_name == 'tags'){
                $datamodel = Tag::find($id);  
                if($datamodel == null){
                    $datamodel = Tag::where('name','=', $name)->first();                      
                }
                if($datamodel != null){
                    $entity->tags()->attach($datamodel->id);
                }                
            }          

        }
    }

    function emptyPermissions($entity,$connection){

        /**
            SOFT DELETE OLD PERMISSIONS

        **/

        $oldpermissions = $entity->$connection()->get();

        foreach($oldpermissions as $permission){
            $permission->delete();
        }
        
    }

    function processAccessPermissionsInternal($entity_name, $entity, $connection){

        if($connection == 'owners'){
            if(is_array($this->owner_user_permission_id)){
                foreach($this->owner_user_permission_id as $permission){
                    $access = User::find($permission);
                    $this->internalPermissionAttach('owner','owners', $access, $entity); 
                }
            } else if (is_array($this->owner_team_permission_id)){
                foreach($this->owner_team_permission_id as $permission){
                    $access = Team::find($permission);
                    $permission = new Permission;
                    $this->internalPermissionAttach('owner','owners', $access, $entity); 
                }
            } else if (is_array($this->owner_region_permission_id)){
                foreach($this->owner_region_permission_id as $permission){
                    $access = Region::find($permission);
                    $this->internalPermissionAttach('owner','owners', $access, $entity);
                }
            } else if (is_array($this->owner_program_permission_id)){
                foreach($this->owner_program_permission_id as $permission){
                    $access = Program::find($permission);
                    $this->internalPermissionAttach('owner','owners', $access, $entity);
                }
            } else if (is_array($this->owner_organization_permission_id)){
                foreach($this->owner_organization_permission_id as $permission){
                    $access = Organization::find($permission);
                    $this->internalPermissionAttach('owner','owners', $access, $entity);
                }
            }
        }

        if($connection == 'viewability'){
            if(is_array($this->viewability_user_permission_id)){
                foreach($this->viewability_user_permission_id as $permission){
                    $access = User::find($permission);
                    $this->internalPermissionAttach($this->viewability_permission_type,'viewability',$access, $entity); 
                }
            } else if (is_array($this->viewability_team_permission_id)){
                foreach($this->viewability_team_permission_id as $permission){
                    $access = Team::find($permission);
                    $permission = new Permission;
                    $this->internalPermissionAttach($this->viewability_permission_type,'viewability', $access, $entity); 
                }
            } else if (is_array($this->viewability_region_permission_id)){
                foreach($this->viewability_region_permission_id as $permission){
                    $access = Region::find($permission);
                    $this->internalPermissionAttach($this->viewability_permission_type,'viewability', $access, $entity); 
                }
            } else if (is_array($this->viewability_program_permission_id)){
                foreach($this->viewability_program_permission_id as $permission){
                    $access = Program::find($permission);
                    $this->internalPermissionAttach($this->viewability_permission_type,'viewability', $access, $entity); 
                }
            } else if (is_array($this->viewability_organization_permission_id)){
                foreach($this->viewability_organization_permission_id as $permission){
                    $access = Organization::find($permission);
                    $this->internalPermissionAttach($this->viewability_permission_type,'viewability', $access, $entity); 
                }
            }
        }        
    }

    function internalPermissionAttach($type, $accessname, $access, $entity){
        $permission = new Permission;
        $permission->type = $type;
        $permission->save();   
        $access->permissions()->attach($permission->id);
        $entity->$accessname()->attach($permission->id);          
    }

    function processAccessPermissionString($string, $entity_name, $entity, $connection){


        /** 

            EXPLODE PERMISSIONS INTO SET ARRAYS & LOOP

            PERMISSION SYNTAX IS:

            [User:8] Steve Rogers (owner)
            [$Model:$Model->ID] $Model->Name ($Permission->Type)

        **/

        $datas = (explode(",",$string));

        foreach($datas as $data){

            /** 
                PROCESS DATA STRING TO EXTRACT KEY VARIABLES BASED ON ABOVE SYNTAX
                REMOVE WHITE SPACE + REDUNANCIES
            **/

            $accessfull = $this->get_string_between($data,'[',']');
            $accessfull = preg_replace('/\s+/', '', $accessfull);
            //$accessfull.'-';
            $accessname = substr($accessfull, 0, (strpos($accessfull, ":")));
            //$accessname.'-';
            $accessid = substr($accessfull, (strpos($accessfull, ":") + 1));
            //$accessid.'-';           
            $permissiontype = $this->get_string_between($data,'(',')');
            $permissiontype = preg_replace('/\s+/', '', $permissiontype);
            //$permissiontype.'-';
            $name = $this->get_string_between($data,']','(');
            $name = trim($name);
            $name = html_entity_decode($name);
            //$name.'-';

            /**

                Check Proposed Relation Model Exists;

            **/

            $datamodel = null;

            $accessname = strtolower($accessname);
            if($accessname == 'user'){
                $datamodel = User::find($accessid);  
                if($datamodel == null){
                    $datamodel = User::where('full_name','=',$name)->first();                        
                }
            }
            if($accessname == 'team'){
                $datamodel = Team::find($accessid);   
                if($datamodel == null){
                    $datamodel = Team::where('name','=',$name)->first();                         
                }                             
            }
            if($accessname == 'region'){
                $datamodel = Region::find($accessid);   
                if($datamodel == null){
                    $datamodel = Region::where('name','=',$name)->first();                         
                }                   
            }
            if($accessname == 'program'){
                $datamodel = Program::find($accessid);  
                if($datamodel == null){
                    $datamodel = Program::where('name','=',$name)->first();                          
                }             
            }
            if($accessname == 'organization'){
                $datamodel = Organization::find($accessid);     
                if($datamodel == null){
                    $datamodel = Organization::where('name','=',$name)->first();                        
                }            
            }

            /**

                CREATE NEW PERMISSION & ATTACH RELATION
    
            **/

            if($datamodel != null){

                $accessname = $accessname.'s';
                $permission = new Permission;
                $permission->type = $permissiontype;
                $permission->save();   
                $permission->$accessname()->attach($datamodel->id);
                $permission->$entity_name()->attach($entity->id);  
      
            }

        }
    }

    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
 
    /**
        IMPORT FORM FIELDS
        Owner Options
    **/       

    public function getOwnerOrganizationPermissionIdOptions(){

        $results = [];

        $collection = [];

        $collection = Organization::all();
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }  

    public function getOwnerProgramPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->owner_organization_permission_id = post('ImportOptions.owner_organization_permission_id');

        if(is_array($this->owner_organization_permission_id)){

            $ids = $this->owner_organization_permission_id;

            $collection = Program::whereHas('organization', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = Program::all();  

        }
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;

    } 

    public function getOwnerRegionPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->owner_program_permission_id = post('ImportOptions.owner_program_permission_id');

        if(is_array($this->owner_program_permission_id)){

            $ids = $this->owner_program_permission_id;

            $collection = Region::whereHas('program', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = Region::all();  

        }
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }

    public function getOwnerTeamPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->owner_region_permission_id = post('ImportOptions.owner_region_permission_id');

        if(is_array($this->owner_region_permission_id)){

            $ids = $this->owner_region_permission_id;

            $collection = Team::whereHas('regions', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = Team::all();  

        }

        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }      

    public function getOwnerUserPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->owner_team_permission_id = post('ImportOptions.owner_team_permission_id');

        if(is_array($this->owner_team_permission_id)){

            $ids = $this->owner_team_permission_id;

            $collection = User::whereHas('teams', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = User::all();  

        }

        foreach($collection as $collect){
            $results[$collect->id] = $collect->full_name;
        }

        return $results;
    }     

    /**
        IMPORT FORM FIELDS
        Viewability Options
    **/

    public function getViewabilityOrganizationPermissionIdOptions(){

        $results = [];

        $collection = [];

        $collection = Organization::all();
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }  

    public function getViewabilityProgramPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->viewability_organization_permission_id = post('ImportOptions.viewability_organization_permission_id');

        if(is_array($this->viewability_organization_permission_id)){

            $ids = $this->viewability_organization_permission_id;

            $collection = Program::whereHas('organization', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = Program::all();  

        }
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;

    } 

    public function getViewabilityRegionPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->viewability_program_permission_id = post('ImportOptions.viewability_program_permission_id');

        if(is_array($this->viewability_program_permission_id)){

            $ids = $this->viewability_program_permission_id;

            $collection = Region::whereHas('program', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = Region::all();  

        }
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }

    public function getViewabilityTeamPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->viewability_region_permission_id = post('ImportOptions.viewability_region_permission_id');

        if(is_array($this->viewability_region_permission_id)){

            $ids = $this->viewability_region_permission_id;

            $collection = Team::whereHas('regions', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = Team::all();  

        }

        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }      

    public function getViewabilityUserPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->viewability_team_permission_id = post('ImportOptions.viewability_team_permission_id');

        if(is_array($this->viewability_team_permission_id)){

            $ids = $this->viewability_team_permission_id;

            $collection = User::whereHas('teams', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->get();

        } else {

            $collection = User::all();  

        }

        foreach($collection as $collect){
            $results[$collect->id] = $collect->full_name;
        }

        return $results;
    } 

    /**
        IMPORT FORM FIELDS
        Category + Tag Options

    **/

    public function getCategoryTypeOptions(){

        $results = [];

        $categories = Category::isPost()->get();

        foreach($categories as $category){
            $results[$category->id] = $category->name;
        }

        return $results;

    } 

    public function getTagTypeOptions(){

        $results = [];

        $tags = Tag::all();

        foreach($tags as $tag){
            $results[$tag->id] = $tag->name;
        }

        return $results;

    } 

    /**
        IMPORT FORM FIELDS
        Responsive Option Enable / Disable
    **/

    public function filterFields($fields, $context = null)
    {
        if ($this->owner_permission_access != null) {
            if(is_array($this->owner_permission_access)){

                if(in_array('users',$this->owner_permission_access)){
                    $fields->owner_user_permission_id->hidden = false;
                }
                if(in_array('teams',$this->owner_permission_access)){
                    $fields->owner_team_permission_id->hidden = false;
                }   
                if(in_array('regions',$this->owner_permission_access)){
                    $fields->owner_region_permission_id->hidden = false;
                }   
                if(in_array('programs',$this->owner_permission_access)){
                    $fields->owner_program_permission_id->hidden = false;
                }   
                if(in_array('organizations',$this->owner_permission_access)){
                    $fields->owner_organization_permission_id->hidden = false;
                } 
            }                                       
        } 

        if ($this->viewability_permission_access != null) {
            if(is_array($this->viewability_permission_access)){

                if(in_array('users',$this->viewability_permission_access)){
                    $fields->viewability_user_permission_id->hidden = false;
                }
                if(in_array('teams',$this->viewability_permission_access)){
                    $fields->viewability_team_permission_id->hidden = false;
                }   
                if(in_array('regions',$this->viewability_permission_access)){
                    $fields->viewability_region_permission_id->hidden = false;
                }   
                if(in_array('programs',$this->viewability_permission_access)){
                    $fields->viewability_program_permission_id->hidden = false;
                }   
                if(in_array('organizations',$this->viewability_permission_access)){
                    $fields->viewability_organization_permission_id->hidden = false;
                } 
            }                                       
        } 

    }
}