<?php namespace Codengine\Awardbank\Components;

use Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Prize;
use Codengine\Awardbank\models\Award;
use Codengine\Awardbank\models\Credit;
use Codengine\Awardbank\models\Transaction;
use Codengine\Awardbank\models\Point;
use Codengine\Awardbank\models\PointAllocation;
use Codengine\Awardbank\models\ActivityLog;
use Codengine\Awardbank\models\Permission;
use Codengine\Awardbank\models\PermissionAccessAllocation;
use Codengine\Awardbank\models\PermissionEntityAllocation;
use Codengine\Awardbank\models\Program;
use Codengine\Awardbank\models\Organization;
use Codengine\Awardbank\models\Region;
use Codengine\Awardbank\models\Team;
use Auth;
use AjaxException;
use Redirect;
use Log;
use Flash;
use RainLab\User\Models\User;

class Prizes extends ComponentBase
{
    /** MODELS **/

    public $prizes; 
    public $prize;
    public $awards;
    public $point;

    /** RELATIONSHIP VARS **/

    public $permissions;
    private $entities;
    private $access;
    private $accessRelation;
    public $permission_type;
    public $permission_access;
    public $permission_access_value;
    public $permission_of;

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

    /** QUERY FILTER VARS **/

    public $sortBy;
    public $sortOrder;

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
    public $hasAccess;
    public $programprimarycolor;
    public $programsecondarycolor;

    public function componentDetails()
    {
        return [
            'name' => 'Prize Component',
            'description' => 'All Front End Front & End Admin Prize Functions.',
        ];
    }

    public function defineProperties()
    {
        return [

            'moduleHeading' => [
                'title'       => 'Module Heading',
                'type'        => 'string',
                'default'     => 'Prizes',
            ],   

            'showHeading' => [
                'title' => 'Show Heading',
                'type'=> 'checkbox',
                'default' => true,
            ],

            'moduleViewTemplate' => [
                'title'       => 'Module View Template',
                'type'        => 'dropdown',
                'default'     => 'prizesearch',
                'options'     => [
                    'prizesearch' => 'Prize Search', 
                    'prizelist' => 'Prize List',
                    'prizedetail' => 'Prize Details'
                ],
            ], 

            'moduleFAIcon' => [
                'title'       => 'Header Icon',
                'type'        => 'string',
                'default'     => ' diamond',
            ],

            'moduleFramework' => [
                'title'       => 'Grid Length Framework',
                'type'        => 'dropdown',
                'default'     => 16,
                'options'     => [12 => 'Bootstrap', 16 => 'Semantic'],
            ], 

            'permissions' => [
                'title'       => 'Permissions Type Selection',
                'type'        => 'set',
                'default'     => ['owner'],
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
                    'user'=>'User', 
                    'team'=>'Team',
                    'region'=>'Region',
                    'program'=>'Program',
                    'organization'=>'Organisation',                                                            
                ]
            ], 

            'entities' => [
                'title'       => 'Permissions Entity Relationship',
                'type'        => 'set',
                'default'     => ['prizes'],
                'placeholder' => 'Select Entity Relationship',
                'items'     => [
                    'prizes'=>'Prizes', 
                    'prizes'=>'Prizes'
                ]
            ],

            'categoryexclusions' => [
                'title'       => 'Categories Exclusions',
                'description'       => 'Categories To Be Excluded From This Module',               
                'type'        => 'set',
            ], 

            'moduleGridCols' => [
                 'title'             => 'Module Grid Columns',
                 'description'       => 'The number of grid columns of prizes in module',
                 'default'           => 12,
                 'type'              => 'string',
                 'validationPattern' => '^[0-9]+$',
                 'validationMessage' => 'The Module Grid Columns property can contain only numeric symbols'
            ],

            'prizesPerRow' => [
                 'title'             => 'Prizes Per Row ',
                 'description'       => 'The number of prizes per module row',
                'type'        => 'dropdown',
                'default'     => 4,
                'options'     => [1 => 1, 2 => 2,3 =>3, 4 => 4, 5=>5, 6=>6, 8 => 8],
            ],

            'maxPosts' => [
                 'title'             => 'Max Prizes',
                 'description'       => 'The maximum prizes per module',
                 'default'           => 12,
                 'type'              => 'string',
                 'validationPattern' => '^[0-9]+$',
                 'validationMessage' => 'The Max Items property can contain only numeric symbols'
            ],

            'maxImages' => [
                'title'             => 'Maximum images display',
                'description'       => 'The number of images per module',
                'type'              => 'dropdown',
                'default'           => 5,
                'options'           => [4 => 4, 5=>5, 6=>6, 8 => 8]
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

        //$this->categories = $this->loadCategories(true);
        //$this->tags = $this->loadTags();

        /** MODULE DISPLAY VARS **/

        $this->moduleHeading = $this->property('moduleHeading');
        $this->showHeading = $this->property('showHeading');
        $this->moduleFAIcon = $this->property('moduleFAIcon');
        $this->moduleViewTemplate = $this->property('moduleViewTemplate');
        $this->moduleFramework = $this->property('moduleFramework');       
        $this->moduleWidth = $this->property('moduleGridCols');
        $this->postsPerRow = $this->property('postsPerRow');
        $this->maxPosts = $this->property('maxPosts');

        /** QUERY FILTER VARS **/

        // $this->sortBy = $this->property('sortBy');
        // $this->sortOrder = $this->property('sortOrder');  
        $this->sortBy = 'id';
        $this->sortOrder = 'desc';

        /** HIDE / REVEAL VARS **/

        //$this->pagination = $this->property('pagination');

    }

    public function onRun(){

        $this->setVars();

        /**IF PRODUCT ID IN URL, RETRIVE AND PASS TO VARIABLE**/

        if(null !== $this->param('prize_id')) {

            $this->prize = Prize::find($this->param('prize_id'));
            
        } else {

            if ($this->moduleViewTemplate == "prizecreate"){
                $this->awards = $this->loadAwards();
            }else{
                $this->prizes = $this->loadPrizes();
            }

        }

    }

    protected function loadAwards()
    {
        $awards = Award::orderBy($this->sortBy, $this->sortOrder);

        if($awards != null){

            $awards = $this->awardQueryFilters($awards)->get();
            
        }

        return $awards;

    }

    protected function loadPrizes()
    {
        $prizes = Prize::orderBy($this->sortBy, $this->sortOrder)->get();

        return $prizes;

    }

    public function getThisAccessRelation(){

        /**CREATE BASE QUERY FOR ACCESS LEVEL FROM USER SESSION**/

        if($this->access == 'organizations'){

            /**WILL NEED TO PASS A PROGRAM SESSION IN HERE IN PLACE OF FIRST**/

            $this->accessRelation = $this->user->currentProgram->organization;

        } elseif ($this->access == 'programs') {

            /**WILL NEED TO PASS A PROGRAM SESSION IN HERE IN PLACE OF FIRST**/

            $this->accessRelation = $this->user->currentProgram;

        /** } elseif ($this->access == 'regions') {

            

            $this->accessRelation = $this->user->teams->first()->regions->first();

            **/

        } elseif ($this->access == 'teams'){

            $this->accessRelation = $this->user->teams->first();

        } else {

            $this->accessRelation = $this->user;    
                    
        }

    }

    public function awardQueryFilters($awards){

        if($this->permissions == 'cascade' || $this->permissions == 'direct' || $this->permissions == 'ascend'){

            $relation = 'viewability';

        } else {

            $relation = $this->permissions;

        }

        $awards->whereHas($relation, function($query) 
        {
            $query->whereHas($this->access, function($query) 
            {
                $query->where('permissionaccessallocatable_id','=', $this->accessRelation->id);

            });
        });

        return $awards;      

    }    

    /** ON POST TO DROPDOWN CHANGE **/
    public function onSelectPermission()
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

    public function onPrizeCreate(){

        $this->setVars();

        /* Check for available point on point source selected */
        $toid = post('pointsourcename');
        $to = post('pointsource');
        $prizepoint = post('totalpoint');
        $this->point = 0;

        $this->point += $this->getMyPoints($to, $toid);

        if ($this->point >= $prizepoint){
            $prize = new Prize();
            $prize->name = post('name');
            $prize->order = post('order');
            $prize->award_id = post('award');
            $prize->save();

            /* Point enought to allocate to the prize */
            $this->allocateMyPoints($prize->id, $prizepoint, $toid);

            /* Add Prize Allocation Record to Activity Log*/
            $orderLog = new ActivityLog();
            $orderLog->ref_id = $prize->id;
            $orderLog->activity_type = 'prize';
            $orderLog->activity_description = "Create New Prize";
            $orderLog->value = $prizepoint;
            $orderLog->user_id = $this->user->id;
            $orderLog->ip_address = $_SERVER['REMOTE_ADDR'];
            $orderLog->save();

            return Redirect::to('/prize/' . $prize->id);
        }else{
            Flash::error('Not enough point');
        }
    }

    private function getMyPoints($type, $id){
        
        $point = Point::where('spent', '=', 0)
            ->whereHas('allocations', function($query) {
                $query->where('pointallocatable_type', 'user')
                    ->where('pointallocatable_id', $this->user->id)
                    ->where('current_allocation', 1);                 
            })
            ->get();
        $mypoint = $point->count();
        $mypending = $point->where('pending','=', 1)->count();
        $myavailable = $mypoint - $mypending;

        return $myavailable;
    }

    private function allocateMyPoints($prize_id, $totalpoint, $id){
        
        $pointallocated = 0;
        $allocated = false;
        $allocationlog = [];

        $pointallos = PointAllocation::where('pointallocatable_type', 'user')
                    ->where('pointallocatable_id', $id)
                    ->where('current_allocation', 1)
                    ->whereHas('points', function($query) 
            {
                $query->where('spent','=', 0);

            })->whereNULL('deleted_at')->take($totalpoint)->get();

        foreach ($pointallos as $pointallo) {
            
            $this->allocatePoint($pointallo->points, $prize_id);

            $pointallo->delete();
        }

        /* Add Prize Allocation Record to Activity Log*/
        $orderLog = new ActivityLog();
        $orderLog->ref_id = $prize_id;
        $orderLog->activity_type = 'prize';
        $orderLog->activity_description = "User's points Allocated to Prize";
        $orderLog->value = $pointallos->count();
        $orderLog->user_id = $this->user->id;
        $orderLog->ip_address = $_SERVER['REMOTE_ADDR'];
        $orderLog->save();

        // try{
        //     $trans = Transaction::where('user_id', $id)->whereNull('deleted_at')->get();
        //     foreach ($trans as $tran) {
        //         $points = Point::where('credit_id', $tran->id)->where('spent', '!=' ,1)->get();

        //         foreach ($points as $point) {
        //             $point->spent = 2;
        //             $point->save();

        //             $poallo = $this->allocatePoint($point, $prize_id);
        //             $pointallocated++;
        //             array_push($allocationlog, $poallo);

        //             if ($pointallocated >= $totalpoint){
        //                 $allocated = true;
        //                 break;
        //             }
        //         }

        //         if ($allocated)
        //             break;
        //     }
            

        //     if (!$allocated){

        //         foreach ($credits as $credit) {
        //             $points = Point::where('credit_id', $credit->id)->where('spent','!=' ,1)->get();

        //             foreach ($points as $point) {
        //                 $point->spent = 2;
        //                 $point->save();

        //                 $this->allocatePoint($point, $prize_id);
        //                 $pointallocated++;
        //                 array_push($allocationlog, $poallo);

        //                 if ($pointallocated >= $totalpoint){
        //                     $allocated = true;
        //                     break;
        //                 }
        //             }

        //             if ($allocated)
        //                 break;
                    
        //         }
                
        //     }
        // }catch(\Illuminate\Database\QueryException $e){
        //     /* if point allocation error, return all points that has been allocated */

        //     Log::error($allocationlog);
        // }

    }

    private function allocatePoint($point, $prize_id){
        $poallo = new PointAllocation();
        $poallo->point_id = $point->id;
        $poallo->pointallocatable_type = 'prize';
        $poallo->pointallocatable_id = $prize_id;
        $poallo->current_allocation = 1;
        $poallo->allocator = $this->user->id;
        $poallo->save();

        return $poallo;
    }

}
