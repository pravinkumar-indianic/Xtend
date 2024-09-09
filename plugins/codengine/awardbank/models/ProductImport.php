<?php 
namespace Codengine\Awardbank\Models;
use Codengine\Awardbank\Models\Product as Product;
use Codengine\Awardbank\Models\Supplier as Supplier;
use Codengine\Awardbank\Models\Category as Category;
use Codengine\Awardbank\Models\Tag as Tag;
use RainLab\User\Models\User as User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \System\Models\File as File;
use BackendAuth;
use Db;
use Storage;
use Session;

class ProductImport extends \Codengine\Awardbank\Models\ImportModel
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
        'wishlist_override',
        'wishlist_permission_access',
        'wishlist_permission_type',
        'wishlist_user_permission_id',
        'wishlist_team_permission_id',
        'wishlist_region_permission_id',
        'wishlist_program_permission_id',
        'wishlist_organization_permission_id',    
        'cart_override',
        'cart_permission_access',
        'cart_permission_type',
        'cart_user_permission_id',
        'cart_team_permission_id',
        'cart_region_permission_id',
        'cart_program_permission_id',
        'cart_organization_permission_id',            
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

                //dump($data);
                /**
                    GET EXISTING PRODUCT IF ID COLUMN EXISTS AND PRODUCT WITH THAT ID EXISTS.
                    OTHERWISE CREATE NEW PRODUCT.

                **/

                $feature_image = null;
                $update = false;

                if(isset($data['id']) && !empty($data['id'])){

                    $product = Product::find($data['id']);

                    if($product == null){

                        $product = new Product;

                    } else {

                        $update = true;
                    }

                } else if(((isset($data['supplier']) && !empty($data['supplier'])) || ($this->import_supplier != null)) && (isset($data['model_number']) && !empty($data['model_number']))){ 

                    if($this->import_supplier != null){

                        $supplier = Supplier::find($this->import_supplier);

                    } else {

                        if(ctype_digit($data['supplier'])){
                            $supplier = Supplier::find($data['supplier']);
                        } else {
                            $suppliername = trim($data['supplier']);
                            $suppliername = html_entity_decode($suppliername);
                            $supplier = Supplier::where('name', $suppliername)->first();
                        } 
                    }

                    if($supplier != null){
                        
                        $product = Product::where('supplier_id','=',$supplier->id)->where('model_number','=',$data['model_number'])->first();

                        if($product == null){

                            $product = new Product;

                        } else {
                            $update = true;
                        }                       

                    } else {
                        $product = new Product;                        
                    }

                } else {

                    $product = new Product;

                }

                if(isset($data['name']) && !empty($data['name'])){
                    $product->name = $data['name'];
                } 

                if($product->name == null) {
                    $product->name = 'No Name Found / Error On Import';
                }

                if(isset($data['model_number']) && !empty($data['model_number'])){
                    $product->model_number = $data['model_number'];
                }

                if(isset($data['active']) && !empty($data['active'])){
                    if(strtolower($data['active']) == 'yes' || $data['active'] == 1){
                        $product->active = 1;
                        $product->activate_after = now();
                    } else {
                        $product->active = 0;                       
                    }
                }

                if(isset($data['featured']) && !empty($data['featured'])){
                    if(strtolower($data['featured']) == 'yes' || $data['featured'] == 1){
                        $product->featured = 1;
                    } else {
                        $product->featured = 0;                       
                    }
                }

                if(isset($data['deliver_base']) && !empty($data['deliver_base'])){
                    if(strtolower($data['deliver_base']) == 'yes' || $data['deliver_base'] == 1){
                        $product->deliver_base = 1;
                    } else {
                        $product->deliver_base = 0;                       
                    }
                }


                /**REPLACE SUPPLIER WITH EXCEL INFO**/

                if($this->import_supplier != null){

                    $product->supplier_id = $this->import_supplier;

                } else {

                    if(isset($data['supplier']) && !empty($data['supplier'])){

                        if(ctype_digit($data['supplier'])){
                            $supplier = Supplier::find($data['supplier']);
                        } else {
                            $suppliername = trim($data['supplier']);
                            $suppliername = html_entity_decode($suppliername);
                            $supplier = Supplier::where('name', $suppliername)->first();
                        }

                        if(isset($supplier->id)){
                            $product->supplier_id = $supplier->id;
                        } else {
                            $product->supplier_id = null;                           
                        }
                        
                    } 

                }   

                if(isset($data['feature_image']) && !empty($data['feature_image'])){
                    //dump($data['feature_image']);
                    if(strtolower(substr($data['feature_image'],0,4)) == 'http'){
                        $product->external_image_url = $data['feature_image'];
                    } else if (strtolower(substr($data['feature_image'],0,4)) == 'https'){
                        $product->external_image_url = $data['feature_image'];
                    } else {
                        /**
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
                            **/

                            $url = 'https://s3-ap-southeast-2.amazonaws.com/xtendsystem/media/products/10106/'.$data['feature_image'];
                            $product->external_image_url = $url;
                            //dump($url);
                           //dump($image);
                           /**
                            $directories = Storage::allDirectories('/media/products/');
                            $directories[] = 'media/products/';
                            foreach($directories as $path){
                                dump($path);
                                //if(Storage::get('/'.$path.'/'.$data['feature_image'])){
                                    //dump(Storage::get('/'.$path.'/'.$data['feature_image']));
                                    //$feature_image = Storage::get('/media/officeclosure.jpg');
                                    /**
                                    $feature_image = new File;
                                    $feature_image->data = base_path('storage/app/'.$path.'/'.$data['feature_image']);
                                    $feature_image->data;
                                    $feature_image->is_public = true;
                                    $feature_image->save();
                                    **/
                                //}
                            //}
                        
                        //}

                       //dump($feature_image);
                        //dump(Storage::get('officeclosure.jpg'));
                    }
                } else if(isset($data['feature_image']) && empty($data['feature_image'])){
                    $feature_image = 'remove';
                }

                if(isset($data['external_image_url']) && !empty($data['external_image_url'])){
                    $product->external_image_url = $data['external_image_url'];
                }

                if(isset($data['created_at']) && !empty($data['created_at'] && $data['created_at'] != "")){
                    $xml=simplexml_load_string($data['created_at']);
                    $product->created_at = $xml['datetime'];
                }  

                if(isset($data['updated_at']) && !empty($data['updated_at'] && $data['updated_at'] != "")){
                    $xml=simplexml_load_string($data['updated_at']);
                    $product->created_at = $xml['datetime'];
                }     

                if(isset($data['deleted_at']) && !empty($data['deleted_at'] && $data['deleted_at'] != "")){
                    $xml=simplexml_load_string($data['deleted_at']);
                    $product->created_at = $xml['datetime'];
                }   

                if(isset($data['deactivated_at']) && !empty($data['deactivated_at'] && $data['deactivated_at'] != "")){
                    $xml=simplexml_load_string($data['deactivated_at']);
                    $product->deactivated_at = $xml['datetime'];
                } 

                if(isset($data['primary_color']) && !empty($data['primary_color'])){
                    $product->primary_color = $data['primary_color'];
                }   

                if(isset($data['brand']) && !empty($data['brand'])){
                    $product->brand = $data['brand'];
                }      

                if(isset($data['hexvalue']) && !empty($data['hexvalue'])){
                    $product->hexvalue = $data['hexvalue'];
                }                                                                                               
                if(isset($data['category-string']) && !empty($data['category-string'])){
                    $string = 'category-string';

                    $product->$string = $data['category-string'];
                } 

                if(isset($data['category-string-check']) && !empty($data['category-string-check'])){
                    $fieldval = 0;
                    $string = 'category-string-check';
                    if(strtolower($data['category-string-check']) == 'yes' || strtolower($data['category-string-check'] == 'true') || $data['category-string-check'] == 1){
                        $fieldval = 1;
                    }
                    $product->$string = $fieldvals;
                }                       

                if(isset($data['cost_currency']) && !empty($data['cost_currency'])){
                    $product->cost_currency = strtolower($data['cost_currency']);
                }                      

                if(isset($data['cost_ex']) && !empty($data['cost_ex'])){
                    $value = preg_replace('/[\$,]/', '', $data['cost_ex']);
                    $value = floatval($value);
                    $product->cost_ex = $value;
                }

                if($product->cost_ex == null && (!isset($data['cost_ex']) || empty($data['cost_ex']))){                
                    $product->cost_ex = 0;
                }

                if(isset($data['cost_tax_percent']) && !empty($data['cost_tax_percent'])){
                    $product->cost_tax_percent = $data['cost_tax_percent'];
                }  

                if(isset($data['cost_freight']) && !empty($data['cost_freight'])){
                    $value = preg_replace('/[\$,]/', '', $data['cost_freight']);
                    $value = floatval($value);                    
                    $product->cost_freight = $value;
                }  

                if($product->cost_freight == null && (!isset($data['cost_freight']) || empty($data['cost_freight']))){
                    $product->cost_freight = 0;
                }

                if(isset($data['cost_rrp']) && !empty($data['cost_rrp'])){
                    $value = preg_replace('/[\$,]/', '', $data['cost_rrp']);
                    $value = floatval($value);                    
                    $product->cost_rrp = $value;
                }  

                if($product->cost_rrp == null && (!isset($data['cost_rrp']) || empty($data['cost_rrp']))){
                    $product->cost_rrp = 0;
                }

                if(isset($data['points_source']) && !empty($data['points_source'])){
                    if(strtolower($data['points_source']) == 'calculated' || strtolower($data['points_source']) == 'rrp'){
                        $product->points_source = $data['points_source'];
                    } else {
                        $product->points_source = null;
                    }
                }                  

                if(isset($data['mark_up_override']) && !empty($data['mark_up_override'])){
                    if(strtolower($data['mark_up_override']) == 'yes' || strtolower($data['mark_up_override']) == 'true' || $data['mark_up_override'] == 1){
                        $product->mark_up_override = 1;
                    } else {
                        $product->mark_up_override = 0;                       
                    }
                }  

                if(isset($data['mark_up_integer']) && !empty($data['mark_up_integer'])){
                    if(is_numeric($data['mark_up_integer'])){
                        $product->mark_up_integer = intval($data['mark_up_integer']);
                    } else {
                        $product->mark_up_integer = 0;
                    }
                } 

                if($product->mark_up_integer == null && (!isset($data['mark_up_integer']) || empty($data['mark_up_integer']))){
                    $product->mark_up_integer = 0;
                }

                if(isset($data['mark_up_type']) && !empty($data['mark_up_type'])){
                    if(strtolower($data['mark_up_type']) == 'dollar' || strtolower($data['mark_up_type']) == 'percent'){
                        $product->mark_up_type = strtolower($data['mark_up_type']);
                    } else {
                        $product->mark_up_type = 'percent';                       
                    }
                } 

                if($product->mark_up_type == null && (!isset($data['mark_up_type']) || empty($data['mark_up_type']))){
                    $product->mark_up_type = 'percent';
                }

                if(isset($data['width']) && !empty($data['width'])){
                    $product->width = floatval($data['width']);
                }   

                if(isset($data['height']) && !empty($data['height'])){
                    $product->height =  floatval($data['height']);
                }      

                if(isset($data['length']) && !empty($data['length'])){
                    $product->length =  floatval($data['length']);
                }      

                if(isset($data['weight']) && !empty($data['weight'])){
                    $product->weight =  floatval($data['weight']);
                }                                           

                if(isset($data['description']) && !empty($data['description'])){
                    
                    $description = $this->make_safe_for_utf8_use($data['description']);

                    $description = utf8_encode($description);
                
                    $product->description = $description;
                } 

                if(isset($data['options1']) && !empty($data['options1'] )){

                    if($data['options1'] == 'No Options'){

                        $product->options1 = null;

                    } else {

                        $options1 = $this->processAnOption($data['options1']);
                        $product->options1 = $options1;

                    }
                }    

                if(isset($data['options2']) && !empty($data['options2'])){

                    if($data['options2'] == 'No Options'){

                        $product->options2 = null;

                    } else {  

                        $options2 = $this->processAnOption($data['options2']);
                        $product->options2 = $options2;

                    }
                }    

                if(isset($data['options3']) && !empty($data['options3'])){


                    if($data['options3'] == 'No Options'){

                        $product->options3 = null;

                    } else {

                        $options3 = $this->processAnOption($data['options3']);
                        $product->options3 = $options3;

                    }
                }       


                //mp($product);
                $product->save();

                if($feature_image == 'remove'){ 
                    //echo 'removing';
                    if($product->feature_image){
                        $product->feature_image()->delete();
                    }

                } else if($feature_image != null){
                    $product->feature_image()->add($feature_image);
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

                        $this->emptyPermissions($product, 'owners');
                        $this->processAccessPermissionsInternal('products', $product, 'owners');

                    } else {

                        $this->emptyPermissions($product, 'owners');                        
                        $this->processAccessPermissionString($data['my_owner'],'products', $product, 'owners');
                        $this->processAccessPermissionsInternal('products', $product, 'owners');                        

                    }
                } 

                if((isset($data['my_view'])) || (is_array($this->viewability_permission_access)) || ($this->viewability_override == true)){

                    if(!isset($data['my_view'])){
                        $data['my_view'] = '';
                    }

                    if($this->viewability_override == true){

                        $this->emptyPermissions($product, 'viewability');
                        $this->processAccessPermissionsInternal('products', $product, 'viewability');

                    } else {

                        $this->emptyPermissions($product, 'viewability');                        
                        $this->processAccessPermissionString($data['my_view'],'products', $product, 'viewability');
                        $this->processAccessPermissionsInternal('products', $product, 'viewability');                        

                    }
                } 

                if((isset($data['my_wishlist'])) || (is_array($this->wishlist_permission_access)) || ($this->wishlist_override == true)){

                    if(!isset($data['my_wishlist'])){
                        $data['my_wishlist'] = '';
                    }

                    if($this->viewability_override == true){

                        $this->emptyPermissions($product, 'wishlist');
                        $this->processAccessPermissionsInternal('products', $product, 'wishlist');

                    } else {

                        $this->emptyPermissions($product, 'wishlist');                        
                        $this->processAccessPermissionString($data['my_wishlist'],'products', $product, 'wishlist');
                        $this->processAccessPermissionsInternal('products', $product, 'wishlist');                        

                    }
                } 

                if((isset($data['my_cart'])) || (is_array($this->cart_permission_access)) || ($this->cart_override == true)){

                    if(!isset($data['my_cart'])){
                        $data['my_cart'] = '';
                    }

                    if($this->viewability_override == true){

                        $this->emptyPermissions($product, 'cart');
                        $this->processAccessPermissionsInternal('products', $product, 'cart');

                    } else {

                        $this->emptyPermissions($product, 'cart');                        
                        $this->processAccessPermissionString($data['my_cart'],'products', $product, 'cart');
                        $this->processAccessPermissionsInternal('products', $product, 'cart');                        

                    }
                }                 

                if((isset($data['my_categories'])) || (is_array($this->category_type)) || ($this->category_override == true)){
                    if($this->category_override == true){
                        if($product->categories){
                            $product->categories()->detach();
                        }
                        $this->processCategoriesOrTagInternal($product, 'categories');
                    } else {
                        if($product->categories){
                            $product->categories()->detach();
                        }
                        $this->processCategoriesOrTagInternal($product, 'categories');
                        if(isset($data['my_categories'])){
                            $dataString = $data['my_categories'];
                        } else {
                            $dataString = '';
                        }                   
                        $this->processCategoriesOrTagString($dataString,'categories', $product);                                           
                    }

                } 

                if((isset($data['my_tags'])) || (is_array($this->tag_type)) || ($this->tag_override == true)){
                    if($this->tag_override == true){
                        if($product->tags){
                            $product->tags()->detach();
                        }
                        $this->processCategoriesOrTagInternal($product, 'tags');
                    } else {
                        if($product->tags){
                            $product->tags()->detach();
                        }
                        $this->processCategoriesOrTagInternal($product, 'tags');    
                        if(isset($data['my_tags'])){
                            $dataString = $data['my_categories'];
                        } else {
                            $dataString = '';
                        }                                                              
                        $this->processCategoriesOrTagString($dataString,'tags', $product);                                           
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

        $result = [];

        foreach($entity->categories as $category){

            if(!array_key_exists($category->id, $result)){

                $result[$category->id] = $category->name;
            }
        }

        $entity->category_array = json_encode($result); 

        $entity->save();   

        //dump($entity);
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

        if($connection == 'wishlist'){
            if(is_array($this->wishlist_user_permission_id)){
                foreach($this->wishlist_user_permission_id as $permission){
                    $access = User::find($permission);
                    $this->internalPermissionAttach($this->wishlist_permission_type,'wishlist',$access, $entity); 
                }
            } else if (is_array($this->wishlist_team_permission_id)){
                foreach($this->wishlist_team_permission_id as $permission){
                    $access = Team::find($permission);
                    $permission = new Permission;
                    $this->internalPermissionAttach($this->wishlist_permission_type,'wishlist', $access, $entity); 
                }
            } else if (is_array($this->wishlist_region_permission_id)){
                foreach($this->wishlist_region_permission_id as $permission){
                    $access = Region::find($permission);
                    $this->internalPermissionAttach($this->wishlist_permission_type,'wishlist', $access, $entity); 
                }
            } else if (is_array($this->wishlist_program_permission_id)){
                foreach($this->wishlist_program_permission_id as $permission){
                    $access = Program::find($permission);
                    $this->internalPermissionAttach($this->wishlist_permission_type,'wishlist', $access, $entity); 
                }
            } else if (is_array($this->wishlist_organization_permission_id)){
                foreach($this->wishlist_organization_permission_id as $permission){
                    $access = Organization::find($permission);
                    $this->internalPermissionAttach($this->wishlist_permission_type,'wishlist', $access, $entity); 
                }
            }
        }  

        if($connection == 'cart'){
            if(is_array($this->cart_user_permission_id)){
                foreach($this->cart_user_permission_id as $permission){
                    $access = User::find($permission);
                    $this->internalPermissionAttach($this->cart_permission_type,'cart',$access, $entity); 
                }
            } else if (is_array($this->cart_team_permission_id)){
                foreach($this->cart_team_permission_id as $permission){
                    $access = Team::find($permission);
                    $permission = new Permission;
                    $this->internalPermissionAttach($this->cart_permission_type,'cart', $access, $entity); 
                }
            } else if (is_array($this->cart_region_permission_id)){
                foreach($this->cart_region_permission_id as $permission){
                    $access = Region::find($permission);
                    $this->internalPermissionAttach($this->cart_permission_type,'cart', $access, $entity); 
                }
            } else if (is_array($this->cart_program_permission_id)){
                foreach($this->cart_program_permission_id as $permission){
                    $access = Program::find($permission);
                    $this->internalPermissionAttach($this->cart_permission_type,'cart', $access, $entity); 
                }
            } else if (is_array($this->cart_organization_permission_id)){
                foreach($this->cart_organization_permission_id as $permission){
                    $access = Organization::find($permission);
                    $this->internalPermissionAttach($this->cart_permission_type,'cart', $access, $entity); 
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
        Supplier Options
    **/

    public function getImportSupplierOptions(){

        $results = [];
        $suppliers = Supplier::all();
        foreach($suppliers as $supplier){
            $results[$supplier->id] = $supplier->name;
        }
        return $results;

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
        Wishlist Options
    **/

    public function getWishlistOrganizationPermissionIdOptions(){

        $results = [];

        $collection = [];

        $collection = Organization::all();
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }  

    public function getWishlistProgramPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->wishlist_organization_permission_id = post('ImportOptions.wishlist_organization_permission_id');

        if(is_array($this->wishlist_organization_permission_id)){

            $ids = $this->wishlist_organization_permission_id;

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

    public function getWishlistRegionPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->wishlist_program_permission_id = post('ImportOptions.wishlist_program_permission_id');

        if(is_array($this->wishlist_program_permission_id)){

            $ids = $this->wishlist_program_permission_id;

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

    public function getWishlistTeamPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->wishlist_region_permission_id = post('ImportOptions.wishlist_region_permission_id');

        if(is_array($this->wishlist_region_permission_id)){

            $ids = $this->wishlist_region_permission_id;

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

    public function getWishlistUserPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->wishlist_team_permission_id = post('ImportOptions.wishlist_team_permission_id');

        if(is_array($this->wishlist_team_permission_id)){

            $ids = $this->wishlist_team_permission_id;

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
        Cart Options
    **/

    public function getCartOrganizationPermissionIdOptions(){

        $results = [];

        $collection = [];

        $collection = Organization::all();
        
        foreach($collection as $collect){

            $results[$collect->id] = $collect->name;
        }

        return $results;
    }  

    public function getCartProgramPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->cart_organization_permission_id = post('ImportOptions.cart_organization_permission_id');

        if(is_array($this->cart_organization_permission_id)){

            $ids = $this->cart_organization_permission_id;

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

    public function getCartRegionPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->cart_program_permission_id = post('ImportOptions.cart_program_permission_id');

        if(is_array($this->cart_program_permission_id)){

            $ids = $this->cart_program_permission_id;

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

    public function getCartTeamPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->cart_region_permission_id = post('ImportOptions.cart_region_permission_id');

        if(is_array($this->cart_region_permission_id)){

            $ids = $this->cart_region_permission_id;

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

    public function getCartUserPermissionIdOptions(){

        $results = [];

        $collection = [];

        $this->cart_team_permission_id = post('ImportOptions.cart_team_permission_id');

        if(is_array($this->cart_team_permission_id)){

            $ids = $this->cart_team_permission_id;

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

        $categories = Category::isProduct()->get();

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

    public function processAnOption($string){

        //dump((string)$string);
        $string = substr($string, 1);
        $string = substr($string, 0, -1);
        //dump($string);
        $string = explode('],[', $string);
        //dump($string);
    
        $optionsArray = [];

        foreach($string as $option){

            $result = explode(',', $option);

            //dump($result);
            $array = [];
            $array['option_name'] = $result[0];
            $array['option_model_number_prefix'] = $result[1];
            $array['option_model_number_suffix'] = $result[2];
            $array['option_model_number_replace'] = $result[3];
            $optionsArray[] = $array; 
            

        }

        return $optionsArray;

    }

    public function multiexplode ($delimiters,$data) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    /**
        IMPORT FORM FIELDS
        Responsive Option Enable / Disable
    **/


    public function make_safe_for_utf8_use($string) {

        $encoding = mb_detect_encoding($string, "UTF-8,ISO-8859-1,WINDOWS-1252");

        if ($encoding != 'UTF-8') {
            return iconv($encoding, 'UTF-8//TRANSLIT', $string);
        } else {
            return $string;
        }
    }

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

        if ($this->wishlist_permission_access != null) {
            if(is_array($this->wishlist_permission_access)){

                if(in_array('users',$this->wishlist_permission_access)){
                    $fields->wishlist_user_permission_id->hidden = false;
                }
                if(in_array('teams',$this->wishlist_permission_access)){
                    $fields->wishlist_team_permission_id->hidden = false;
                }   
                if(in_array('regions',$this->wishlist_permission_access)){
                    $fields->wishlist_region_permission_id->hidden = false;
                }   
                if(in_array('programs',$this->wishlist_permission_access)){
                    $fields->wishlist_program_permission_id->hidden = false;
                }   
                if(in_array('organizations',$this->wishlist_permission_access)){
                    $fields->wishlist_organization_permission_id->hidden = false;
                } 
            }                                       
        } 

        if ($this->cart_permission_access != null) {
            if(is_array($this->cart_permission_access)){

                if(in_array('users',$this->cart_permission_access)){
                    $fields->cart_user_permission_id->hidden = false;
                }
                if(in_array('teams',$this->cart_permission_access)){
                    $fields->cart_team_permission_id->hidden = false;
                }   
                if(in_array('regions',$this->cart_permission_access)){
                    $fields->cart_region_permission_id->hidden = false;
                }   
                if(in_array('programs',$this->cart_permission_access)){
                    $fields->cart_program_permission_id->hidden = false;
                }   
                if(in_array('organizations',$this->cart_permission_access)){
                    $fields->cart_organization_permission_id->hidden = false;
                } 
            }                                       
        } 

    }

}