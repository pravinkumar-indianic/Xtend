<?php namespace Codengine\Awardbank\Models;

use RainLab\User\Models\User as User;
use Codengine\Awardbank\Models\Point as Point;
use Model;

/**
 * Model
 */
class Permission extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'type',
        'exclusive_filter',
    ];

    protected $jsonable = [
        'access_types',
        'organizations_input',
        'programs_input',
        'regions_input',
        'teams_input',
        'users_input',
    ];

    public $hasMany = [
        'entityallocations' => 'Codengine\Awardbank\Models\PermissionEntityAllocation',
        'accessallocations' => 'Codengine\Awardbank\Models\PermissionAccessAllocation',       
    ];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_permissions';

    public $morphedByMany = [
    
        'suppliers' => ['Codengine\Awardbank\Models\Supplier', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'], 
        'products' => ['Codengine\Awardbank\Models\Product', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'],  
        'orders' => ['Codengine\Awardbank\Models\Order', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'],          
        'posts' => ['Codengine\Awardbank\Models\Post', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'],
        'categories' => ['Codengine\Awardbank\Models\Category', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'],
        'tags' => ['Codengine\Awardbank\Models\Tag', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'],

        'users' => ['Rainlab\User\Models\User', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],     
        'teams' => ['Codengine\Awardbank\Models\Team', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],      
        'regions' => ['Codengine\Awardbank\Models\Region', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],      
        'programs' => ['Codengine\Awardbank\Models\Program', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],      
        'organizations' => ['Codengine\Awardbank\Models\Organization', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],
        
        'addresses' => ['Codengine\Awardbank\Models\Address', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'],

        'awards' => ['Codengine\Awardbank\Models\Award', 'table' => 'codengine_awardbank_permission_entity_allocation', 'name'=>'permissionentityallocatable'],
    ];

    public function beforeSave()
    {




        unset($this->attributes['access_types']);
        unset($this->attributes['organizations_input']);
        unset($this->attributes['programs_input']); 
        unset($this->attributes['regions_input']);   
        unset($this->attributes['teams_input']);  
        unset($this->attributes['users_input']);                         
        unset($this->attributes['exclusive_filter']);        
        //$this->save();

        //dump($this->regions);
    }

    public function afterSave(){   
        //dump(post('Permission'));

        if(post('Permission')){

            $permission = post('Permission');

            if(is_array($permission)){

                $access_types = post('Permission[exclusive_filter]');


                if($access_types == 1){

                    if(isset($permission['organizations_input']) && is_array($permission['organizations_input'])){
                        foreach($permission['organizations_input'] as $access){
                            $this->organizations()->attach($access);
                        }
                    }

                   if(isset($permission['programs_input']) && is_array($permission['programs_input'])){
                        foreach($permission['programs_input'] as $access){
                            $this->programs()->attach($access);
                        }
                   }

                   if(isset($permission['regions_input']) && is_array($permission['regions_input'])){
                        foreach($permission['regions_input'] as $access){
                            $this->regions()->attach($access);
                        }
                   }

                   if(isset($permission['teams_input']) && is_array($permission['teams_input'])){
                        foreach($permission['teams_input'] as $access){
                            $this->teams()->attach($access);
                        }
                   }   

                   if(isset($permission['users_input']) && is_array($permission['users_input'])){
                        foreach($permission['users_input'] as $access){
                            $this->users()->attach($access);
                        }
                   }  
                } else {

                    if(isset($permission['users_input']) && is_array($permission['users_input'])){
                        foreach($permission['users_input'] as $access){
                            $this->users()->attach($access);
                        }
                    } 

                    else if(isset($permission['teams_input']) && is_array($permission['teams_input'])){
                        foreach($permission['teams_input'] as $access){
                            $this->teams()->attach($access);
                        }
                    } 

                    else if(isset($permission['regions_input']) && is_array($permission['regions_input'])){
                        foreach($permission['regions_input'] as $access){
                            $this->regions()->attach($access);
                        }
                    }

                    else if(isset($permission['programs_input']) && is_array($permission['programs_input'])){
                        foreach($permission['programs_input'] as $access){
                            $this->programs()->attach($access);
                        }
                    }

                    else if(isset($permission['organizations_input']) && is_array($permission['organizations_input'])){
                        foreach($permission['organizations_input'] as $access){
                            $this->organizations()->attach($access);
                        }
                    }
                }                                          
            }
        }
    }

    public function scopeIsActive($query)
    {
        return $query->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);   
    }

    public function scopeIsOwner($query)
    {
        return $query->whereIn('type', ['owner','owners'])->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsManager($query)
    {
        return $query->where('type', 'manager')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);;
    }    

    public function scopeIsAlias($query)
    {
        return $query->where('type', 'alias')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsViewable($query)
    {
        return $query->whereIn('type', ['cascade','direct','ascend'])->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsNomination($query)
    {
        return $query->where('type', 'nomination')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsVoteViewable($query)
    {
        return $query->where('type', 'vote')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsWinnerNominationsManager($query)
    {
        return $query->where('type', 'winnernominationsmngr')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsNominationsManager($query)
    {
        return $query->where('type', 'nominationsmngr')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsReadOnly($query)
    {
        return $query->where('type', 'readonly')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsWishlist($query)
    {
        return $query->where('type', 'wishlist')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeIsCart($query)
    {
        return $query->where('type', 'cart')->where('active', true)->where('codengine_awardbank_permissions.deleted_at', null);
    }

    public function scopeInEntity($query, $entitypermissions)
    {
        return $query->whereIn('type', $entitypermissions);
    }

    /** POSSIBLY REDUNDANT - SHAME, BECAUSE CLEVER CODE **/
    
    public function scopeSearchQuery($query, $model, $prm, $opt, $q){
        return $query->whereHas($model, function ($query) use ($prm, $opt, $q) {
                $query->where($prm, $opt, $q);
        });
    }

    public function scopeIsQuery($query, $prm, $opt, $q)
    {
        return $query->where($prm, $opt, $q);
    }

    public function getAccessWhereInsAttribute()
    {

        $result = [];

        if($this->users){
            foreach($this->users as $access){
                $result['user'][] = $access->id;
            }
        }

        if($this->teams){               
            foreach($this->teams as $access){
                $result['team'][] = $access->id;
            }
        }

        if($this->regions){                  
            foreach($this->regions as $access){
                $result['region'][] = $access->id;
            }   
        }

        if($this->programs){                       
            foreach($this->programs as $access){
                $result['program'][] = $access->id;
            }  
        }      

        if($this->organizations){                  
            foreach($this->organizations as $access){
                $result['organization'][] = $access->id;
            }   
        } 

        return $result;       

    }

    public function getAccessNamesAttribute()
    {

        $names = '';      
          
        if($this->users){
            foreach($this->users as $access){
                $names .= '[User:'.$access->id.'] '. $access->name.' '.$access->surname.' ('. $this->type .'), '; 
            }
        }

        if($this->teams){               
            foreach($this->teams as $access){
                $names .= '[Team:'.$access->id.'] '. $access->name.'  ('. $this->type .'), '; 
            }
        }

        if($this->regions){                  
            foreach($this->regions as $access){
                $names .= '[Region:'.$access->id.'] '.$access->name.'  ('. $this->type .'), '; 
            }   
        }

        if($this->programs){                       
            foreach($this->programs as $access){
                $names .= '[Program:'.$access->id.'] '.$access->name.'  ('. $this->type .'), '; 
            }  
        }      

        if($this->organizations){                  
            foreach($this->organizations as $access){
                $names .= '[Organization:'.$access->id.'] '.$access->name.'  ('. $this->type .'), '; 
            }   
        } 

        return $names;
    }

    public function getAccessTotalPointsAttribute()
    {

        $value = 0;      
          
        if($this->users){
            foreach($this->users as $access){
                $point = Point::where('spent', '=', 0)
                ->where('pending','=',0)
                ->whereHas('allocations', function($query) use ($access){
                    $query->where('pointallocatable_type', 'user')
                        ->where('current_allocation','=',1)
                        ->where('pointallocatable_id', $access->id);                   
                })->count();
                $value = $value + $point;              
            }
        }

        if($this->teams){               
            foreach($this->teams as $access){
                $point = Point::where('spent', '=', 0)
                ->where('pending','=',0)
                ->whereHas('allocations', function($query) use ($access){
                    $query->where('pointallocatable_type', 'team')
                        ->where('current_allocation','=',1)
                        ->where('pointallocatable_id', $access->id);                   
                })->count();
                $value = $value + $point;   
            }
        }

        if($this->regions){                  
            foreach($this->regions as $access){
                $point = Point::where('spent', '=', 0)
                ->where('pending','=',0)
                ->whereHas('allocations', function($query) use ($access){
                    $query->where('pointallocatable_type', 'region')
                        ->where('current_allocation','=',1)
                        ->where('pointallocatable_id', $access->id);                   
                })->count();
                $value = $value + $point;   
            }   
        }

        if($this->programs){                       
            foreach($this->programs as $access){
                $point = Point::where('spent', '=', 0)
                ->where('pending','=',0)
                ->whereHas('allocations', function($query) use ($access){
                    $query->where('pointallocatable_type', 'program')
                        ->where('current_allocation','=',1)
                        ->where('pointallocatable_id', $access->id);                   
                })->count();
                $value = $value + $point;   
            }  
        }      

        if($this->organizations){                  
            foreach($this->organizations as $access){
                $point = Point::where('spent', '=', 0)
                ->where('pending','=',0)
                ->whereHas('allocations', function($query) use ($access){
                    $query->where('pointallocatable_type', 'organization')
                        ->where('current_allocation','=',1)
                        ->where('pointallocatable_id', $access->id);                   
                })->count();
                $value = $value + $point;   
            }   
        } 

        return $value;
    }

    public function getPosterAttribute()
    {

        $posters = array();      
          
        if(count($this->users) > 0){
            foreach ($this->users as $user) {
                $poster['type'] = 'profile';
                $poster['id'] = $user->id;
                $poster['name'] = $user->full_name;
                $poster['slug'] = $user->slug;
                if($user->avatar){
                    $poster['image'] = $user->getAvatarThumb();
                } else {
                    $poster['image'] = '//www.gravatar.com/avatar/c59152a77c0bc073fe6f2a3141b99010?s=25&d=mm';
                }
                array_push($posters, $poster);
            }
        }

        if(count($this->teams) > 0){ 
            foreach ($this->teams as $team) {
                $poster['type'] = 'team';
                $poster['id'] = $team->id;
                $poster['name'] = $team->name;
                $poster['slug'] = $team->slug;
                if($team->feature_image){
                    $poster['image'] = $team->feature_image->getThumb(25,25);
                 } else {
                    $poster['image'] = '//www.gravatar.com/avatar/c59152a77c0bc073fe6f2a3141b99010?s=25&d=mm';
                }               
                array_push($posters, $poster); 
            }              
            
        }

        if(count($this->regions) >0 ){ 
            foreach ($this->regions as $region) {
                $poster['type'] = 'region';
                $poster['id'] = $region->id;
                $poster['name'] = $region->name; 
                $poster['slug'] = $region->slug;
                if($region->feature_image){
                    $poster['image'] = $region->feature_image->getThumb(25,25);
                 } else {
                    $poster['image'] = '//www.gravatar.com/avatar/c59152a77c0bc073fe6f2a3141b99010?s=25&d=mm';
                }    
                array_push($posters, $poster);
            }
        }

        if(count($this->programs) > 0){
            foreach ($this->programs as $program) {                   
                $poster['type'] = 'program'; 
                $poster['id'] = $program->id;
                $poster['name'] = $program->name; 
                $poster['slug'] = $program->slug;
                if($program->feature_image){
                    $poster['image'] = $program->feature_image->getThumb(25,25);
                 } else {
                    $poster['image'] = '//www.gravatar.com/avatar/c59152a77c0bc073fe6f2a3141b99010?s=25&d=mm';
                }    
                array_push($posters, $poster);
            }
        }      

        if(count($this->organizations) >0){
            foreach ($this->organizations as $organization) {                  
                $poster['type'] = 'organization';  
                $poster['id'] = $organization->id;
                $poster['name'] = $organization->name; 
                $poster['slug'] = $organization->slug;
                if($organization->feature_image){
                    $poster['image'] = $program->feature_image->getPath();
                 } else {
                    $poster['image'] = null;
                }       
                array_push($posters, $poster);
            }
        } 

        return $posters;
    }

    /*public function getPosterAttribute()
    {

        $posters = null;      
          
        if(count($this->users) > 0){
            $posters = $this->users;
        }

        if(count($this->teams) > 0){               
            $posters = $this->teams;
        }

        if(count($this->regions) >0 ){                  
            $posters = $this->regions;   
        }

        if(count($this->programs) > 0){                       
            $posters = $this->programs; 
        }      

        if(count($this->organizations) >0){                  
            $posters = $this->organizations;  
        } 

        return $posters;
    }*/

    public function getEntityNamesAttribute()
    {

        $names = '';      
          
        if($this->suppliers){
            foreach($this->suppliers as $entity){
                $names .= '[Supplier:'.$entity->id.'] '.$entity->name;
            }
        }

        if($this->products){               
            foreach($this->products as $entity){
                $names .= '[Product:'.$entity->id.'] '.$entity->name.', '; 
            }
        }

        if($this->posts){                  
            foreach($this->posts as $entity){
                $names .= '[Post:'.$entity->id.'] '.$entity->title.', '; 
            }   
        }

        if($this->categories){                  
            foreach($this->categories as $entity){
                $names .= '[Category:'.$entity->id.'] '.$entity->name.', '; 
            }   
        }

        if($this->awards){                  
            foreach($this->awards as $entity){
                $names .= '[Award:'.$entity->id.'] '.$entity->name.', '; 
            }   
        }

        return $names;
    }   

    public function getOrganizationsInputOptions()
    {

        $results = [];
        $types = Organization::all();
        foreach($types as $type){
            $results[$type->id] = $type->name;
        }
        return $results;
    }

    public function getOrganizationsInputAttribute()
    {

        $string = '[';


        if(post('Permission[organizations_input]')){
            $array = post('Permission[organizations_input]');
            $end = end($array);
            foreach($array as $value){
                if ($value == $end) {
                    $string .= $value;
                } else {
                    $string .= $value.',';                    
                }

            }
        }

        $string .= ']';
        return $string;
    }

    public function getProgramsInputOptions()
    {

        $results = [];

        if($this->organizations_input != null && $this->organizations_input != 0){

            if($this->exclusive_filter == false){

                $types = Program::whereHas('organization', function ($query) {
                    $query->whereIn('id',$this->organizations_input);
                })->get(); 
            } else {

                $types = Program::whereHas('organization', function ($query) {
                    $query->whereNotIn('id',$this->organizations_input);
                })->get(); 

            }

        } else {

            $types = Program::all();

        }
        foreach($types as $type){
            $results[$type->id] = $type->name;
        }
        return $results;
    }

    public function getProgramsInputAttribute()
    {

        $string = '[';


        if(post('Permission[programs_input]')){
            $array = post('Permission[programs_input]');
            $end = end($array);
            foreach($array as $value){
                if ($value == $end) {
                    $string .= $value;
                } else {
                    $string .= $value.',';                    
                }
            }
        }

        $string .= ']';
        return $string;
    }

    public function getRegionsInputOptions()
    {     
        $results = [];


        if($this->programs_input != null && $this->programs_input != 0){

            if($this->exclusive_filter == false){

                $types = Region::whereHas('program', function ($query) {
                    $query->whereIn('id',$this->programs_input);
                })->get(); 

            } else {

                $types = Region::whereHas('program', function ($query) {
                    $query->whereNotIn('id',$this->programs_input);
                })->get(); 
            }

        } elseif($this->organizations_input != null && $this->organizations_input != 0){

            if($this->exclusive_filter == false){

                $types = Region::whereHas('program', function ($query) {
                    $query->whereHas('organization', function($query){
                        $query->whereIn('id',$this->organizations_input);
                    });
                })->get(); 

            } else {

                $types = Region::whereHas('program', function ($query) {
                    $query->whereHas('organization', function($query){
                        $query->whereNotIn('id',$this->organizations_input);
                    });
                })->get(); 
            }

        } else {

            $types = Region::all();

        }

        foreach($types as $type){
            $results[$type->id] = $type->name;
        }
        return $results;
    }    

    public function getRegionsInputAttribute()
    {

        $string = '[';


        if(post('Permission[regions_input]')){
            $array = post('Permission[regions_input]');
            $end = end($array);
            foreach($array as $value){
                if ($value == $end) {
                    $string .= $value;
                } else {
                    $string .= $value.',';                    
                }
            }
        }

        $string .= ']';
        return $string;
    }

    public function getTeamsInputOptions()
    {

        $results = [];

        if($this->regions_input != null && $this->regions_input != 0){

            if($this->exclusive_filter == false){

                $types = Team::whereHas('regions', function ($query) {
                    $query->whereIn('id',$this->regions_input);
                })->get(); 

            } else {

                $types = Team::whereHas('regions', function ($query) {
                    $query->whereNotIn('id',$this->regions_input);
                })->get(); 
            }

        } elseif($this->programs_input != null && $this->programs_input != 0){

            if($this->exclusive_filter == false){

                $types = Team::whereHas('regions', function ($query) {
                    $query->whereHas('program', function($query){
                        $query->whereIn('id',$this->programs_input);
                    });                    
                })->get(); 

            } else {

                $types = Team::whereHas('regions', function ($query) {
                    $query->whereHas('program', function($query){
                        $query->whereNotIn('id',$this->programs_input);
                    }); 
                })->get(); 
            }

        } elseif($this->organizations_input != null && $this->organizations_input != 0){

            if($this->exclusive_filter == false){

                $types = Team::whereHas('regions', function ($query) {
                    $query->whereHas('program', function($query){                    
                        $query->whereHas('organization', function($query){
                            $query->whereIn('id',$this->organizations_input);
                        });
                    });
                })->get(); 

            } else {

                $types = Team::whereHas('regions', function ($query) {
                    $query->whereHas('program', function($query){                    
                        $query->whereHas('organization', function($query){
                            $query->whereNotIn('id',$this->organizations_input);
                        });
                    });
                })->get(); 
            }

        } else {

            $types = Team::all();

        }



        foreach($types as $type){
            $results[$type->id] = $type->name;
        }
        return $results;
    }

    public function getTeamsInputAttribute()
    {

        $string = '[';


        if(post('Permission[teams_input]')){
            $array = post('Permission[teams_input]');
            $end = end($array);
            foreach($array as $value){
                if ($value == $end) {
                    $string .= $value;
                } else {
                    $string .= $value.',';                    
                }
            }
        }

        $string .= ']';
        return $string;
    }

    public function getUsersInputOptions()
    {

        $results = [];

        if($this->teams_input != null && $this->teams_input != 0){        

            if($this->exclusive_filter == false){

                $types = User::whereHas('teams', function ($query) {
                    $query->whereIn('id',$this->teams_input);
                })->get(); 

            } else {

                $types = User::whereHas('teams', function ($query) {
                    $query->whereNotIn('id',$this->teams_input);
                })->get(); 
            }

        } elseif($this->regions_input != null && $this->regions_input != 0){

            if($this->exclusive_filter == false){

                $types = User::whereHas('teams', function ($query) {
                    $query->whereHas('regions', function($query){                   
                        $query->whereIn('id',$this->regions_input);
                    });                          
                })->get(); 

            } else {

                $types = User::whereHas('teams', function ($query) {
                    $query->whereHas('regions', function($query){                   
                        $query->whereNotIn('id',$this->regions_input);
                    });                          
                })->get(); 
            }

        } elseif($this->programs_input != null && $this->programs_input != 0){

            if($this->exclusive_filter == false){

                $types = User::whereHas('teams', function ($query) {
                    $query->whereHas('regions', function($query){ 
                        $query->whereHas('program', function($query){                                        
                            $query->whereIn('id',$this->programs_input);
                        });    
                    });                          
                })->get(); 

            } else {

                $types = User::whereHas('teams', function ($query) {
                    $query->whereHas('regions', function($query){ 
                        $query->whereHas('program', function($query){                                        
                            $query->whereNotIn('id',$this->programs_input);
                        });    
                    });                          
                })->get(); 
            }

        } elseif($this->organizations_input != null && $this->organizations_input != 0){

            if($this->exclusive_filter == false){

                $types = User::whereHas('teams', function ($query) {
                    $query->whereHas('regions', function($query){ 
                        $query->whereHas('program', function($query){ 
                            $query->whereHas('organization', function($query){ 
                                $query->whereIn('id',$this->organizations_input);
                            });
                        });    
                    });                          
                })->get(); 

            } else {

                $types = User::whereHas('teams', function ($query) {
                    $query->whereHas('regions', function($query){ 
                        $query->whereHas('program', function($query){ 
                            $query->whereHas('organization', function($query){ 
                                $query->whereNotIn('id',$this->organizations_input);
                            });
                        });    
                    });                          
                })->get(); 
            }

        } else {

            $types = User::all();

        }

        foreach($types as $type){
            $results[$type->id] = $type->full_name;
        }
        return $results;
    }


    public function getUsersInputAttribute()
    {

        $string = '[';


        if(post('Permission[users_input]')){
            $array = post('Permission[users_input]');
            $end = end($array);
            foreach($array as $value){
                if ($value == $end) {
                    $string .= $value;
                } else {
                    $string .= $value.',';                    
                }
            }
        }

        $string .= ']';
        return $string;
    }

    public function filterFields($fields, $context = null)
    {


        if (post('Permission[organizations_input]') != null) {
           $this->organizations_input = post('Permission[organizations_input]');
        }        

        if (post('Permission[programs_input]') != null) {
           $this->programs_input = post('Permission[programs_input]');
        }  

        if (post('Permission[regions_input]') != null) {
           $this->regions_input = post('Permission[regions_input]');
        }  

        if (post('Permission[teams_input]') != null) {
           $this->teams_input = post('Permission[teams_input]');
        }  

        if (post('Permission[users_input]') != null) {
           $this->users_input = post('Permission[users_input]');
        } 
     
        if ($this->access_types != null) {

            $fields->organizations_input->default = [1,2];      
            if(is_array($this->access_types)){

                if(in_array('users',$this->access_types)){
                    if($fields->users_input->hidden == true){                    
                        $fields->users_input->hidden = false;
                    }                      
                }

                if(in_array('teams',$this->access_types)){
                    if($fields->teams_input->hidden == true){                    
                        $fields->teams_input->hidden = false;
                    }   
                } 

                if(in_array('regions',$this->access_types)){
                    if($fields->regions_input->hidden == true){                    
                        $fields->regions_input->hidden = false;
                    }               
                }   

                if(in_array('programs',$this->access_types)){
                    if($fields->programs_input->hidden == true){                    
                        $fields->programs_input->hidden = false;
                    }
                }  

                if(in_array('organizations',$this->access_types)){
                    if($fields->organizations_input->hidden == true){
                        $fields->organizations_input->hidden = false;
                    }                    
                } 
            } 

        }
    }     
}