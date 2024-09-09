<?php 
namespace Codengine\Awardbank\Models;
use Codengine\Awardbank\Models\Supplier as Supplier;
use RainLab\User\Models\User as User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \System\Models\File as File;
use BackendAuth;
use Db;
use Storage;
use Session;

class SupplierImport extends \Codengine\Awardbank\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    protected $guarded = [];

    /**
    protected $fillable = [
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
    ];

    **/

    public $jsonable = [

        'territory',

    ];

    public $rules = [];

    public $table = 'canthis_importexport_import_log';

    /**
     * @var array Relations
     */
    
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

                    $supplier = Supplier::find($data['id']);

                    if($supplier == null){

                        $supplier = new Supplier;

                    } else {

                        $update = true;
                    }

                } else {

                    $supplier = new Supplier;

                }

                if(isset($data['name']) && !empty($data['name'])){
                    $supplier->name = $data['name'];
                }
 
                if(isset($data['description']) && !empty($data['description'])){

                    //$description = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $data['description']));
                    $description = $description;               
                    $supplier->description = $description;  

                } elseif($supplier->description == null || !isset($data['description']) || empty($data['description'])){
                    $supplier->description = '';                  
                }

                if(isset($data['mark_up_integer']) && !empty($data['mark_up_integer'])){
                    if(is_numeric($data['mark_up_integer'])){
                        $supplier->mark_up_integer = intval($data['mark_up_integer']);
                    } else {
                        $supplier->mark_up_integer = 0;
                    }
                } 

                if($supplier->mark_up_integer == null && (!isset($data['mark_up_integer']) || empty($data['mark_up_integer']))){
                    $supplier->mark_up_integer = 0;
                } 

                if(isset($data['mark_up_type']) && !empty($data['mark_up_type'])){
                    if(strtolower($data['mark_up_type']) == 'dollar' || strtolower($data['mark_up_type']) == 'percent'){
                        $supplier->mark_up_type = strtolower($data['mark_up_type']);
                    } else {
                        $supplier->mark_up_type = 'percent';                       
                    }
                } 

                if($supplier->mark_up_type == null && (!isset($data['mark_up_type']) || empty($data['mark_up_type']))){
                    $supplier->mark_up_type = 'percent';
                } 

                if(isset($data['territory']) && !empty($data['territory'])){
                    $supplier->territory = $data['territory'];
                }

                if(isset($data['orders_email']) && !empty($data['orders_email'])){
                    $supplier->orders_email = $data['orders_email'];
                }      

                if(isset($data['cc_primary']) && !empty($data['cc_primary'])){
                    if(strtolower($data['cc_primary']) == 'yes' || $data['cc_primary'] == 1){
                        $product->cc_primary = 1;
                    } else {
                        $product->cc_primary = 0;                       
                    }
                }

                if(isset($data['primary_contact_number']) && !empty($data['primary_contact_number'])){
                    $supplier->primary_contact_number = $data['primary_contact_number'];
                }

                if(isset($data['primary_contact_name']) && !empty($data['primary_contact_name'])){
                    $supplier->primary_contact_name = $data['primary_contact_name'];
                }

                if(isset($data['primary_contact_email']) && !empty($data['primary_contact_email'])){
                    $supplier->primary_contact_email = $data['primary_contact_email'];
                }                

                if(isset($data['cc_secondary']) && !empty($data['cc_secondary'])){
                    if(strtolower($data['cc_secondary']) == 'yes' || $data['cc_secondary'] == 1){
                        $product->cc_secondary = 1;
                    } else {
                        $product->cc_secondary = 0;                       
                    }
                }

                if(isset($data['secondary_contact_number']) && !empty($data['secondary_contact_number'])){
                    $supplier->secondary_contact_number = $data['secondary_contact_number'];
                }

                if(isset($data['secondary_contact_name']) && !empty($data['secondary_contact_name'])){
                    $supplier->secondary_contact_name = $data['secondary_contact_name'];
                }

                if(isset($data['secondary_contact_email']) && !empty($data['secondary_contact_email'])){
                    $supplier->secondary_contact_email = $data['secondary_contact_email'];
                } 

                if(isset($data['primary_color']) && !empty($data['primary_color'])){
                    $supplier->primary_color = $data['primary_color'];
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

                $supplier->save();


                if($feature_image == 'remove'){ 
                    //echo 'removing';
                    if($supplier->feature_image){
                        $supplier->feature_image()->delete();
                    }

                } else if($feature_image != null){
                    $supplier->feature_image()->add($feature_image);
                } 


                if($update == true){
                    $this->logUpdated();
                } else {
                    $this->logCreated();
                }


                if((isset($data['my_owner'])) || (is_array($this->owner_permission_access)) || ($this->owner_override == true)){

                    if(!isset($data['my_owner'])){
                        $data['my_owner'] = '';
                    }

                    if($this->owner_override == true){

                        $this->emptyPermissions($supplier, 'owners');
                        $this->processAccessPermissionsInternal('suppliers', $supplier, 'owners');

                    } else {

                        $this->emptyPermissions($supplier, 'owners');                        
                        $this->processAccessPermissionString($data['my_owner'],'suppliers', $supplier, 'owners');
                        $this->processAccessPermissionsInternal('suppliers', $supplier, 'owners');                        

                    }
                } 

                if((isset($data['my_view'])) || (is_array($this->viewability_permission_access)) || ($this->viewability_override == true)){

                    if(!isset($data['my_view'])){
                        $data['my_view'] = '';
                    }

                    if($this->viewability_override == true){

                        $this->emptyPermissions($supplier, 'viewability');
                        $this->processAccessPermissionsInternal('suppliers', $supplier, 'viewability');

                    } else {

                        $this->emptyPermissions($supplier, 'viewability');                        
                        $this->processAccessPermissionString($data['my_view'],'suppliers', $supplier, 'viewability');
                        $this->processAccessPermissionsInternal('suppliers', $supplier, 'viewability');                        

                    }

                }     

            }
            catch (\Exception $ex) {

                $this->logError($row, $ex->getMessage());

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