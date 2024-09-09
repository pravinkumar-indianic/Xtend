<?php namespace Codengine\Awardbank;

use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use Codengine\Awardbank\Components\ResultsManagement;
use Codengine\Awardbank\Models\Product;
use Codengine\Awardbank\Components\DashboardSettings;
use Codengine\Awardbank\Models\ScorecardResultImport;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Rainlab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Transaction;
use Codengine\Awardbank\Models\PointsLedger;
use Codengine\Awardbank\Models\CronChecker;
use Carbon\Carbon;
use Event;
use Mail;
use Db;
use Backend;
use Illuminate\Support\Facades\Log;
use Codengine\Awardbank\Models\XeroAPI;

class Plugin extends PluginBase
{

    public $require = [
        'RainLab.User',
        'October.Drivers',
        'Responsiv.Uploader',
        'Renatio.DynamicPDF',
        'ShahiemSeymor.Ckeditor'
    ];

    public function pluginDetails()
    {
        return [
            'name'        => 'Xtend',
            'description' => 'Xtend Plugin',
            'author'      => 'Codengine',
            'icon'        => 'icon-trophy'
        ];
    }

    public function registerComponents()
    {
        return [
            'Codengine\Awardbank\Components\Access' => 'access',
            'Codengine\Awardbank\Components\ActivityFeedDashboard' => 'ActivityFeedDashboard',
            //'Codengine\Awardbank\Components\AdminReports' => 'AdminReports',
            //'Codengine\Awardbank\Components\AwardCreate' => 'AwardCreate',
            'Codengine\Awardbank\Components\AwardsDashboard' => 'AwardsDashboard',
            'Codengine\Awardbank\Components\AwardEdit' => 'AwardEdit',
            'Codengine\Awardbank\Components\AwardsList' => 'AwardsList',
            'Codengine\Awardbank\Components\AwardView' => 'AwardView',
            //'Codengine\Awardbank\Components\AwardReport' => 'AwardReport',
            //'Codengine\Awardbank\Components\Awards' => 'Awards',
            'Codengine\Awardbank\Components\Cart' => 'Cart',
            'Codengine\Awardbank\Components\CKEditorFactory' => 'CKEditorFactory',
            'Codengine\Awardbank\Components\ContentBlock1' => 'ContentBlock1',
            'Codengine\Awardbank\Components\ContentBlock2' => 'ContentBlock2',
            'Codengine\Awardbank\Components\CustomPasswordReset' => 'CustomPasswordReset',
            'Codengine\Awardbank\Components\CustomStyles' => 'CustomStyles',
            'Codengine\Awardbank\Components\DashboardSettings' => 'DashboardSettings',
            'Codengine\Awardbank\Components\FAQ' => 'FAQ',
            'Codengine\Awardbank\Components\FooterUsefullInfo' => 'FooterUsefullInfo',
            'Codengine\Awardbank\Components\Gauge' => 'Gauge',
            'Codengine\Awardbank\Components\ImageUploader' => 'imageUploader',
            'Codengine\Awardbank\Components\LeadCreate' => 'LeadCreate',
            'Codengine\Awardbank\Components\Leaderboard' => 'Leaderboard',
            'Codengine\Awardbank\Components\LoginPage' => 'LoginPage',
            //'Codengine\Awardbank\Components\Messaging' => 'Messaging',
            //'Codengine\Awardbank\Components\MyPoint' => 'MyPoint',
            'Codengine\Awardbank\Components\MyProfile' => 'MyProfile',
            'Codengine\Awardbank\Components\MyProgram' => 'MyProgram',
            'Codengine\Awardbank\Components\OrderCreate' => 'OrderCreate',
            'Codengine\Awardbank\Components\OrderView' => 'OrderView',
            'Codengine\Awardbank\Components\Prizes' => 'Prizes',
            'Codengine\Awardbank\Components\Popup' => 'Popup',
            //'Codengine\Awardbank\Components\PostCreate' => 'PostCreate',
            'Codengine\Awardbank\Components\PostEdit' => 'PostEdit',
            'Codengine\Awardbank\Components\PostView' => 'PostView',
            'Codengine\Awardbank\Components\PostsList' => 'PostsList',
            'Codengine\Awardbank\Components\PostsListDashboard' => 'PostsListDashboard',
            'Codengine\Awardbank\Components\PostManagement' => 'PostManagement',
            //'Codengine\Awardbank\Components\Products' => 'Products',
            //'Codengine\Awardbank\Components\Programs' => 'Programs',
            //'Codengine\Awardbank\Components\ProgramComponent' => 'program',
            'Codengine\Awardbank\Components\ProgramToolsDashboard' => 'ProgramToolsDashboard',
            'Codengine\Awardbank\Components\ProgramToolsList' => 'ProgramToolsList',
            'Codengine\Awardbank\Components\ProgramSettings' => 'ProgramSettings',
            'Codengine\Awardbank\Components\ProfilesDashboard' => 'ProfilesDashboard',
            'Codengine\Awardbank\Components\ProfilesList' => 'ProfilesList',
            'Codengine\Awardbank\Components\PreventIEUser' => 'PreventIEUser',
            //'Codengine\Awardbank\Components\Regions' => 'Regions',
            'Codengine\Awardbank\Components\RegisterUser' => 'RegisterUser',
            'Codengine\Awardbank\Components\Renewal' => 'Renewal',
            'Codengine\Awardbank\Components\ResultsDashboard' => 'ResultsDashboard',
            'Codengine\Awardbank\Components\ResultsDetail' => 'ResultsDetail',
            'Codengine\Awardbank\Components\ResultsManagement' => 'ResultsManagement',
            'Codengine\Awardbank\Components\ResultsWidget' => 'ResultsWidget',
            'Codengine\Awardbank\Components\RewardsList' => 'RewardsList',
            'Codengine\Awardbank\Components\RewardsCoverFlow' => 'RewardsCoverFlow',
            'Codengine\Awardbank\Components\RewardView' => 'RewardView',
            'Codengine\Awardbank\Components\RewardsNav' => 'RewardsNav',
            'Codengine\Awardbank\Components\RewardsCategoryTiles' => 'RewardsCategoryTiles',
            'Codengine\Awardbank\Components\RewardsSettings' => 'RewardsSettings',
            //'Codengine\Awardbank\Components\Suppliers' => 'Suppliers',
            'Codengine\Awardbank\Components\Scorecard' => 'Scorecard',
            'Codengine\Awardbank\Components\Sidebar' => 'Sidebar',
            'Codengine\Awardbank\Components\Signup' => 'Signup',
            'Codengine\Awardbank\Components\SpinTheWin' => 'SpinTheWin',
            'Codengine\Awardbank\Components\SocialFeed' => 'SocialFeed',
            'Codengine\Awardbank\Components\SocialFeedSettings' => 'SocialFeedSettings',
            'Codengine\Awardbank\Components\SpinTheWinManagement' => 'SpinTheWinManagement',
            'Codengine\Awardbank\Components\SwitchProgram' => 'SwitchProgram',
            'Codengine\Awardbank\Components\TransactionManager' => 'TransactionManager',
            //'Codengine\Awardbank\Components\Teams' => 'Teams',
            'Codengine\Awardbank\Components\ToyotaCentralLogin' => 'ToyotaCentralLogin',
            'Codengine\Awardbank\Components\UserManagement' => 'UserManagement',
            'Codengine\Awardbank\Components\WishlistDashboard' => 'WishlistDashboard',
            //'Codengine\Awardbank\Components\WishlistDisplay' => 'WishlistDisplay',
            //'Codengine\Awardbank\Components\UploaderTest' => 'UploaderTest',

        ];
    }

    public function registerSettings()
    {
        return [
            'PlatformSettings' => [
                'label'       => 'Platform Settings',
                'description' => 'Platform Settings',
                'category'    => 'Platform Settings',
                'icon'        => 'icon-cog',
                'class'       => 'Codengine\Awardbank\Models\Settings',
                'order'       => 100,
                'keywords'    => 'Platorm',
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'codengine.awardbank.content_manager' => [
                'label' => 'Content Manager Permission',
                'tab' => 'Awardbank',
                'order' => 1,
            ],
            'codengine.awardbank.supplier_manager' => [
                'label' => 'Supplier Manager Permission',
                'tab' => 'Awardbank',
                'order' => 2,
            ],
            'codengine.awardbank.user_manager' => [
                'label' => 'User Manager Permission',
                'tab' => 'Awardbank',
                'order' => 3,
            ],
            'codengine.awardbank.financial_manager' => [
                'label' => 'Financial Manager Permission',
                'tab' => 'Awardbank',
                'order' => 4,
            ],
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'Codengine\Awardbank\ReportWidgets\Anniversaries' => [
                'label'   => 'Anniversaries',
                'context' => 'dashboard',
            ],
            'Codengine\Awardbank\ReportWidgets\Processes' => [
                'label'   => 'Processes',
                'context' => 'dashboard',
            ],
        ];
    }

    public function boot()
    {
        Event::listen('backend.menu.extendItems', function($manager)
        {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'users' => [
                    'label'       => 'rainlab.user::lang.users.menu_label',
                    'url'         => Backend::url('rainlab/user/users'),
                    'icon'        => 'icon-user',
                    'permissions' => ['rainlab.users.*'],
                    'order'       => 100,
                ],
                'import' => [
                    'label'       => 'Import Users',
                    'url'         => Backend::url('codengine/awardbank/usersimportexport/import'),
                    'icon'        => 'icon-sign-in',
                    'order'       => 200,
                ],
                'export' => [
                    'label'       => 'Export Users',
                    'url'         => Backend::url('codengine/awardbank/usersimportexport/export'),
                    'icon'        => 'icon-sign-out',
                    'order'       => 300,
                ],
            ]);
        });

        UserModel::extend(function($model) {

            $model->addFillable([
                'slug',
                'birth_date',
                'commencement_date',
                'full_name',
                'title',
                'name',
                'surname',
                'birth_date',
                'email',
                'is_activated',
                'password',
                'can_buy_points',
                'points_limit',
                'external_reference',
                'company_position',
            ]);

            $model->addJsonable([
                'register_form_questions_answers',
                'points_json',
                'owner_array',
                'cart_array',
                'wishlist_array',
                'current_all_teams_id',
                'current_all_regions_id',
                'current_all_programs_id',
                'current_all_orgs_id',
            ]);

            $model->addDateAttribute('commencement_date');
            $model->addDateAttribute('birth_date');

            $model->belongsTo['billingcontact'] = ['Codengine\Awardbank\Models\BillingContact', 'key' => 'billingcontact_id', 'otherKey' => 'id'];
            $model->belongsToMany['teams'] = ['Codengine\AwardBank\Models\Team', 'table' => 'codengine_awardbank_u_t'];
            $model->belongsToMany['teams_manager'] = ['Codengine\AwardBank\Models\Team', 'table' => 'codengine_awardbank_u_tm'];
            $model->belongsToMany['programs'] = ['Codengine\AwardBank\Models\Program', 'table' => 'codengine_awardbank_u_p'];
            $model->belongsToMany['programs_manager'] = ['Codengine\AwardBank\Models\Program', 'table' => 'codengine_awardbank_u_pm'];
            $model->belongsToMany ['targetingtags'] = ['Codengine\Awardbank\Models\TargetingTag', 'table' => 'codengine_awardbank_tt_user','key' => 'targeting_tag_id', 'otherKey' => 'user_id'];
            $model->morphToMany['permissions'] = ['Codengine\Awardbank\Models\Permission', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'];
            $model->morphToMany['owner'] = [
                'Codengine\Awardbank\Models\Permission',
                'table' => 'codengine_awardbank_permission_access_allocation',
                'name'=> 'permissionaccessallocatable',
                'scope' => 'isOwner',
            ];
            $model->hasMany['transactions'] = ['Codengine\AwardBank\Models\Transaction'];
            $model->hasMany['results'] = ['Codengine\Awardbank\Models\Result'];
            $model->hasMany ['credits'] = ['Codengine\AwardBank\Models\Credit'];
            $model->hasMany ['sentmessages'] = ['Codengine\Awardbank\Models\Message', 'key' => 'sender_id', 'otherKey' => 'id'];
            $model->hasMany ['receivedmessages'] = ['Codengine\Awardbank\Models\Message', 'key' => 'receiver_id', 'otherKey' => 'id'];
            $model->hasMany ['billing_invoices'] = ['Codengine\AwardBank\Models\BillingInvoices'];
            $model->hasMany ['orders'] = ['Codengine\Awardbank\Models\Order',  'key' => 'user_id', 'otherKey' => 'id'];
            $model->hasMany ['sentthankyous'] = ['Codengine\Awardbank\Models\Thankyou', 'key' => 'sender_id', 'otherKey' => 'id'];
            $model->hasMany ['receivedthankyous'] = ['Codengine\Awardbank\Models\Thankyou', 'key' => 'receiver_id', 'otherKey' => 'id'];
            $model->hasMany['pointsledgers'] = ['Codengine\Awardbank\Models\PointsLedger'];
            $model->hasMany ['receivednominations'] = ['Codengine\Awardbank\Models\Nomination', 'key' => 'nominated_user_id', 'otherKey' => 'id'];
            $model->hasMany ['sentnominations'] = ['Codengine\Awardbank\Models\Nomination', 'key' => 'created_user_id', 'otherKey' => 'id'];
            $model->hasMany ['activityfeeds'] = ['Codengine\Awardbank\Models\ActivityFeed'];
            $model->hasMany ['wonprizes'] = ['Codengine\Awardbank\Models\Prize', 'table' => 'codengine_awardbank_p_w','key' => 'prize_id', 'otherKey' => 'user_id'];
            $model->hasOne ['currentProgram'] = ['Codengine\Awardbank\Models\Program','key' => 'id', 'otherKey' => 'current_program_id'];
            $model->hasOne ['currentRegion'] = ['Codengine\Awardbank\Models\Region','key' => 'current_region_id', 'otherKey' => 'id'];
            $model->hasOne ['currentTeam'] = ['Codengine\Awardbank\Models\Team','key' => 'id', 'otherKey' => 'current_team_id'];
            $model->hasOne ['homeAddress'] = ['Codengine\Awardbank\Models\Address','key' => 'id', 'otherKey' => 'home_address_id'];
            $model->hasOne ['shippingAddress'] = ['Codengine\Awardbank\Models\Address','key' => 'id', 'otherKey' => 'shipping_address_id'];

            $model->bindEvent('model.beforeSave', function() use ($model) {

                if($model->full_name != '' && $model->full_name != null){
                    $pieces = explode(" ", $model->full_name);
                    if(isset($pieces[0])){
                        $model->name = $pieces[0];
                    }
                    $loops = count($pieces);
                    $loops = $loops - 1;
                    $i = 1;
                    $string = '';
                    while($i <= $loops){
                        $string .= ' '.$pieces[$i];
                        $i++;
                    }
                    $model->surname = $string;
                }

                $model->full_name = $model->name.' '.$model->surname;

                if ($model->slug == null){
                    $model->slug = str_slug($model->name.' '.$model->surname.' '.time().uniqid());
                }

                $dirty = $model->getDirty();

                if(array_key_exists('today_birth_date', $dirty)){
                    $model->updated_birth_date = Carbon::now();
                }

                if(array_key_exists('today_commencement_date', $dirty)){
                    $model->updated_commencement_date = Carbon::now();
                }

                if($model->new_birthday != null){
                    $model->new_birthday = $this->dateConvert($model->new_birthday);
                }
                if($model->new_tenure != null){
                    $model->new_tenure = $this->dateConvert($model->new_tenure);
                }

                $model->bounceDownRelations($model);

            });

            $model->addDynamicMethod('getMyFullnameAttribute', function() use ($model) {
                return $model->name.' '.$model->surname;
            });

            $model->addDynamicMethod('getMyThankyouCount', function() use ($model) {
                return $model->receivedthankyous->count();
            });

            $model->addDynamicMethod('getBirthdayAttribute', function() use ($model) {
                return is_null($model->new_birthday) ? null : Carbon::parse($model->new_birthday);
            });

            $model->addDynamicMethod('getTenureAttribute', function() use ($model) {
                return is_null($model->new_tenure) ? null : Carbon::parse($model->new_tenure);
            });


            /**
            Save down the users Team, Region, Program, Organization relationships into the model based on the current program access so they can be readily retrieved
             **/

            $model->addDynamicMethod('bounceDownRelations', function() use ($model) {
                if($model){
                    $fullset = $model->load('teams','programs','programs.organization','programs.regions','programs_manager','teams_manager');
                    $this->flattenAllAccessArray($model);
                    $this->setCurrentAccess($model);
                    $this->flattenManagementArray($model);
                }
            });

            /**
            Set Required Attributes To Swap The Users Current Program
             **/

            $model->addDynamicMethod('switchProgram', function($programid) use ($model) {
                if($model){
                    $programsarray = $model->programs->pluck('id')->toArray();
                    if(in_array($programid,$programsarray)){
                        $program = Program::find($programid);
                        if($program){
                            $team = $program->teams()->whereHas('users', function($query) use ($model){
                                $query->where('id','=',$model->id);
                            })->first();
                            $model->current_team_id = $team->id ?? null;
                            $model->current_region_id = null;
                            $model->current_program_id = $program->id;
                            $model->current_org_id = $program->organization->id;
                            $model->current_territory = $program->territory;
                            $model->save();
                        }
                    }
                }
            });


            $model->addDynamicMethod('getTargetArray', function() use ($model) {

                $managementArray = $model->owner_array;
                $teamsArray = $model->current_all_teams_id;

                $organizationArray = [$model->current_org_id];
                $programArray = [$model->current_program_id];
                $regionArrray = [];
                $teamArray =[];

                if(!isset($managementArray['teams'])){

                    $managementArray['teams'] = [];
                }

                $program = Program::where('id','=', $model->current_program_id)->with(['regions.teams'])->first();

                foreach($program->regions ?? [] as $region){
                    $regionArray[] = $region->id;
                    foreach($region->teams as $team){
                        $teamArray[] = $team->id;
                        if(!array_key_exists($team->id, $managementArray['teams']) && in_array($team->id, $teamsArray)){
                            $team = Team::find($team->id);
                            $managementArray['teams'][$team->id] = $team->name;
                        }
                    }
                }

                if(isset($managementArray['organizations'])){
                    foreach($managementArray['organizations'] as $key =>  $value){
                        if(!in_array($key,$organizationArray)){
                            unset($managementArray['organizations'][$key]);
                        }
                    }
                }

                if(isset($managementArray['programs'])){
                    foreach($managementArray['programs'] as $key => $value){
                        if(!in_array($key,$programArray)){
                            unset($managementArray['programs'][$key]);
                        }
                    }
                }

                /**
                if(isset($managementArray['regions'])){
                foreach($managementArray['regions'] as $key => $value){
                if(!in_array($key,$regionArray)){
                unset($managementArray['regions'][$key]);
                }
                }
                }
                 **/

                if(isset($managementArray['teams'])){
                    foreach($managementArray['teams'] as $key => $value){
                        if(!in_array($key,$teamArray)){
                            unset($managementArray['teams'][$key]);
                        }
                    }
                }

                return $managementArray;

            });

            $model->addDynamicMethod('checkIfOwner', function($entity) use ($model) {

                $result = false;

                $permissions = $entity->owners;

                $array = $model->getTargetArray();

                foreach($permissions as $permission){

                    if(isset($array['organizations'])){
                        foreach($permission->organizations as $value){
                            if(in_array($value->id, $array['organizations'])){
                                $result = true;
                            }
                        }
                    }

                    if(isset($array['programs'])){
                        foreach($permission->programs as $value){
                            if(in_array($value->id, $array['programs'])){
                                $result = true;
                            }
                        }
                    }

                    if(isset($array['regions'])){
                        foreach($permission->regions as $value){
                            if(in_array($value->id, $array['regions'])){
                                $result = true;
                            }
                        }
                    }

                    if(isset($array['teams'])){
                        foreach($permission->regions as $value){
                            if(in_array($value->id, $array['regions'])){
                                $result = true;
                            }
                        }
                    }

                    foreach($permission->users as $owner){
                        if($model->id == $owner->id){
                            $result = true;
                        }
                    }
                }

                return $result;

            });

            $model->addDynamicMethod('runMyPoints', function($reprocess = false) use ($model) {
                $highId = PointsLedger::select('id')->where('user_id','=',$model->id)->where('program_id','=',$model->current_program_id)->orderBy('id','DESC')->first();
                $array = $model->points_json;
                if(($reprocess == false) && $highId){
                    if(isset($array[$model->current_program_id])){
                        if(isset($array[$model->current_program_id]['highId'])){
                            if($model->points_json[$model->current_program_id]['highId'] == $highId->id){
                                return false;
                            }
                        }
                    }
                }

                $hasABaseLine = PointsLedger::select('id','points_value')->where('user_id','=',$model->id)->where('program_id','=',$model->current_program_id)->where('type','=',0)->orderBy('id','DESC')->first();

                if(!$hasABaseLine){
                    $points = 0;
                    $startid = 0;
                    $highId = 0;
                } else {
                    $points = $hasABaseLine->points_value;
                    $startid = $hasABaseLine->id;
                    $highId = $highId->id;
                }

                $additions = PointsLedger::where('id','>=',$startid)->where('user_id','=',$model->id)->where('program_id','=',$model->current_program_id)->where('type','=',1)->sum('points_value');
                $subtractions = PointsLedger::where('id','>=',$startid)->where('user_id','=',$model->id)->where('program_id','=',$model->current_program_id)->where('type','=',2)->sum('points_value');
                $inCart = PointsLedger::where('id','>=',$startid)->where('user_id','=',$model->id)->where('program_id','=',$model->current_program_id)->where('type','=',3)->sum('points_value');
                $outCart = PointsLedger::where('id','>=',$startid)->where('user_id','=',$model->id)->where('program_id','=',$model->current_program_id)->where('type','=',4)->sum('points_value');

                $cartCurrent = $inCart - $outCart;
                $pointsjson = $model->points_json;
                $currentpoints = $points + $additions - $subtractions - $cartCurrent;
                $name = $model->currentProgram->name;

                $pointsjson[$model->current_program_id] = [
                    'name' => $name,
                    'points' => $currentpoints,
                    'inCart' => $cartCurrent,
                    'highId' => $highId,
                ];
                $model->points_json = $pointsjson;
                $model->current_points = $currentpoints;
                $model->pending_points = $cartCurrent;
                //Use a new User instance instead because $model->save() was changing the thank_yous sender_id to null
                //The reason why is this happening is unknown, probably some relationship triggers/hooks and
                //maybe because the model is not properly populated on boot
                //$model->save();
                $user = User::find($model->id);
                if ($user) {
                    $user->points_json = $pointsjson;
                    $user->current_points = $currentpoints;
                    $user->pending_points = $cartCurrent;
                    $user->save();
                }
            });

            $model->addDynamicMethod('getAddressByType', function($type) use ($model) {
                $return = null;
                $permissions = $model->permissions()->IsActive()
                    ->whereHas('addresses', function($query) use ($type) {
                        $query->where('type', $type);
                    })
                    ->get();

                foreach($permissions as $permission){
                    $return = $permission->addresses()->first();
                }

                return $return;

            });


            $model->addDynamicMethod('sendEmailActivation', function() use ($model) {

                $randomstring = md5(microtime());
                $model->email_random_string = $randomstring;
                $model->save();

                $randomurl = '/'.$model->slug.'/'.$randomstring;

                $roles = [1,3,4];

                $backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();

                $toemail = $model->email;
                //$toemail = 'hq@xtendsystem.com';
                $program = Program::find($model->current_program_id);
                $programname = $program->name;
                $programpointsname =  $program->points_name;
                if($program->login_image){
                    $program_image_url = $program->login_image->path;
                } else {
                    $program_image_url = null;
                }
                $userpointsvalue = $model->current_points;
                $receiverfullname = $model->full_name;
                $receiverfirstname = $model->name;
                $programslug = $program->slug;
                $title = 'Please Validate Your E-mail Address';
                $prebuiltbody = '<p>To reset your password, please follow <a href="https://app.xtendsystem.com/confirm-email'.$randomurl.'">this link</a>.</p><p>Thanks Xtend Team</p>';
                $templateName = 'xtenddefault-confirm-email-template';

                $template = new Template($templateName);
                $vars = [
                    'programname' => $programname,
                    'pointsname' => $programpointsname,
                    'pointsvalue' => $userpointsvalue,
                    'programimageurl' => $program_image_url,
                    'title' => $title,
                    'programslug' => $programslug,
                    'randomurl' => $randomurl,
                    'receiverfirstname' => $receiverfirstname,
                    'receiverfullname' => $receiverfullname,
                    'prebuiltbody' => $prebuiltbody,
                ];

                $message = new \Addgod\MandrillTemplate\Mandrill\Message();
                $message->setSubject($title);
                $message->setFromEmail('noreply@xtendsystem.com');
                /*$message->setFromEmail('noreplay@xtendsystem.com');
                $message->setFromName('Xtend System');*/
                $message->setMergeVars($vars);

                $recipient = new Recipient($toemail, null, Recipient\Type::TO);
                $recipient->setMergeVars($vars);
                $message->addRecipient($recipient);

                MandrillTemplateFacade::send($template, $message);
            });



            $model->addDynamicMethod('sendPwordResetEmail', function() use ($model) {

                $randomstring = md5(microtime());
                $model->email_random_string = $randomstring;
                $model->save();

                $pwordreseturl = '/reset-password/'.$model->slug.'/'.$randomstring;

                $roles = [1,3,4];

                $backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();

                $toemail = $model->email;
                //$toemail = 'hq@xtendsystem.com';
                $program = Program::find($model->current_program_id);
                if($program){
                    $programname = $program->name;
                    $programpointsname = $program->points_name;
                    $programslug = $program->slug;
                    if($program->login_image){
                        $program_image_url = $program->login_image->path;
                    } else {
                        $program_image_url = null;
                    }
                } else {
                    $programname = 'Never Logged In.';
                    $programpointsname = 'Points';
                    $program_image_url = null;
                    $programslug = 'empty';
                }
                $userpointsvalue = $model->current_points;
                $receiverfullname = $model->full_name;
                $receiverfirstname = $model->name;
                $title = 'Password Reset Link';
                $prebuiltbody = '<p>To reset your password, please follow <a href="https://app.xtendsystem.com'.$pwordreseturl.'">this link</a>.</p><p>Thanks Xtend Team</p>';
                $templateName = 'xtenddefault-password-reset-template';

                $template = new Template($templateName);
                $vars = [
                    'programname' => $programname,
                    'pointsname' => $programpointsname,
                    'pointsvalue' => $userpointsvalue,
                    'programimageurl' => $program_image_url,
                    'title' => $title,
                    'programslug' => $programslug,
                    'pwordreseturl' => $pwordreseturl,
                    'receiverfirstname' => $receiverfirstname,
                    'receiverfullname' => $receiverfullname,
                    'prebuiltbody' => $prebuiltbody,
                ];

                $message = new \Addgod\MandrillTemplate\Mandrill\Message();
                $message->setSubject($title);
                $message->setFromEmail('noreply@xtendsystem.com');
                /*$message->setFromEmail('noreplay@xtendsystem.com');
                $message->setFromName('Xtend System');*/
                $message->setMergeVars($vars);

                $recipient = new Recipient($toemail, null, Recipient\Type::TO);
                $recipient->setMergeVars($vars);
                $message->addRecipient($recipient);

                MandrillTemplateFacade::send($template, $message);
            });

            $model->addDynamicMethod('activationProgramInvite', function($program) use ($model) {

                if($model->is_activated == false || $model->email_confirmed == false){
                    $randomstring = md5(microtime());
                    $model->email_random_string = $randomstring;
                    $model->save();
                    $pwordreseturl = '/reset-password/'.$model->slug.'/'.$randomstring;
                } else {
                    $pwordreseturl = null;
                }

                $roles = [1,3,4];

                $backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();

                $toemail = $model->email;
                //$toemail = 'hq@xtendsystem.com';

                if($program){
                    $programname = $program->name;
                    $programpointsname = $program->points_name;
                    $programslug = $program->slug;
                    if($program->login_image){
                        $program_image_url = $program->login_image->path;
                    } else {
                        $program_image_url = null;
                    }
                } else {
                    $programname = 'Never Logged In.';
                    $programpointsname = 'Points';
                    $program_image_url = null;
                    $programslug = 'empty';
                }
                $userpointsvalue = $model->current_points;
                $receiverfullname = $model->full_name;
                $receiverfirstname = $model->name;
                $title = 'You have been invited to join the '.$programname.' Program.';
                if($model->is_activated == false || $model->email_confirmed == false){
                    $prebuiltbody = '<p>You have been invited to join the '.$programname.' program on Xtend!</p><p>To accept this invitation, please follow <a href="https://'.$programslug.'.xtendsystem.com'.$pwordreseturl.'">this link</a> to set your password for the first time and activate your account.</p><p>Thanks Xtend Team</p>';
                } else {
                    $prebuiltbody = '<p>You have been invited to join the '.$programname.' program on Xtend!</p><p>You will be able to view this program next time you <a href="https://'.$programslug.'.xtendsystem.com/">login to Xtend</a>!</p><p>Thanks Xtend Team</p>';
                }

                $templateName = 'xtenddefault-program-active-template-xtend-2-0';

                $template = new Template($templateName);
                $vars = [
                    'programname' => $programname,
                    'pointsname' => $programpointsname,
                    'pointsvalue' => $userpointsvalue,
                    'programimageurl' => $program_image_url,
                    'title' => $title,
                    'programslug' => $programslug,
                    'pwordreseturl' => $pwordreseturl,
                    'receiverfirstname' => $receiverfirstname,
                    'receiverfullname' => $receiverfullname,
                    'prebuiltbody' => $prebuiltbody,
                ];

                $message = new \Addgod\MandrillTemplate\Mandrill\Message();
                $message->setSubject($title);
                $message->setFromEmail('noreply@xtendsystem.com');
                /*$message->setFromEmail('noreplay@xtendsystem.com');
                $message->setFromName('Xtend System');*/
                $message->setMergeVars($vars);

                $recipient = new Recipient($toemail, null, Recipient\Type::TO);
                $recipient->setMergeVars($vars);
                $message->addRecipient($recipient);

                MandrillTemplateFacade::send($template, $message);

            });



            $model->bindEvent('user.register', function() use ($model) {
                $model->full_name = $model->name.' '.$model->surname;
            });

            $model->addFillable([
                'slug',
                'can_buy_points',
                'points_limit',
                'external_reference',
                'new_tenure',
                'new_birthday',
                'username',
                'password',
                'name',
                'surname',
                'email',
                'is_activated',
                'phone_number',
                'company_position',
                'current_territory',
                't_and_c_accept',
                'email_confirmed',
                'business_name',
            ]);
        });

        UsersController::extend(function($controller){

            $controller->addDynamicProperty('importExportConfig', null);

            if(!isset($controller->implement['Backend.Behaviors.RelationController']))

                $controller->implement[] = 'Backend.Behaviors.RelationController';

            $controller->relationConfig  =  '$/codengine/awardbank/controllers/users/config_relations.yaml';

        });

        /** Codengine Events **/

        // SEND AN EMAIL FACTORY
        // $toemails is a value array of emails to send to.
        // $ccemails is a valuearray of emails to CC to.
        // $vars is a key => value array of variables for the email template
        // $template is a string name of the Mailchimp template to use.

        Event::listen('xtend.sendEmail', function($toemails,$ccemails,$vars, $template, $subject = 'A message from EVT') {
            $template = new Template($template);
            $message = new \Addgod\MandrillTemplate\Mandrill\Message();
            if(!empty($subject)) {
                $message->setSubject($subject);
            }

            $message->setMergeVars($vars);
            $message->setFromEmail('noreply@xtendsystem.com');

            foreach ($toemails as $toemail) {
                $recipient = new Recipient($toemail, null, Recipient\Type::TO);
                $recipient->setMergeVars($vars);
                $message->addRecipient($recipient);
            }

            foreach ($ccemails as $ccemail) {
                $ccRecipient = new Recipient($ccemail, null, Recipient\Type::CC);
                $message->setMergeVars($vars);
                $message->addRecipient($ccRecipient);
            }

            MandrillTemplateFacade::send($template, $message);
        });

        // GET A RANDOM STRING FOR IDs
        // $length = length of string - default = 5

        Event::listen('xtend.getRandomString', function($length = 5) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        });

        Event::listen('xtend.checkModuleValid', function($user,$name) {
            if($user->currentProgram){
                if($user->currentProgram->$name == 1){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });

        //

        Event::listen('xtend.getUserAnniversaries', function($field)
        {
            $users = UserModel::whereRaw('DAYOFYEAR(curdate()) = DAYOFYEAR(DATE_ADD('.$field.', INTERVAL (YEAR(NOW()) - YEAR('.$field.')) YEAR))')->get(['id'])->toArray();
            return $users;
        });

        Event::listen('xtend.runningcron', function () {
            Log::debug('Task is running');
        });

        Event::listen('xtend.updateAnniversaryFlags', function($type)
        {
            $now = Carbon::now();
            $results = Event::fire('xtend.getUserAnniversaries', [$type], true);
            if($type == 'new_birthday'){
                UserModel::where('id','>=', 1)->update(['today_birth_date' => 0,'updated_birth_date' => $now]);

                UserModel::whereIn('id',$results)->update(['today_birth_date' => 1,'updated_birth_date' => $now]);
            }
            if($type == 'new_tenure'){
                UserModel::where('id','>=', 1)->update(['today_commencement_date' => 0,'updated_commencement_date' => $now]);

                UserModel::whereIn('id',$results)->update(['today_commencement_date' => 1,'updated_commencement_date' => $now]);
            }
        });


        Event::listen('xtend.sendTenureEmail', function()
        {
            $users = UserModel::where('today_commencement_date','=',1)->get();
            foreach($users as $user){

                $carbon = new Carbon($user->new_tenure);
                $today = new Carbon();
                $diffyears = $carbon->diffInYears($today);
                $toemails = [];
                $toemails[] = $user->email;
                $ccemails = ['hq@evtmarketing.com.au'];

                $tenurearray = [];
                if(isset( $user->currentProgram) && isset($user->currentProgram->tenure_array)) {
                    $tenurearray = $user->currentProgram->tenure_array;
                }

                $email_template = 'test-xtend-default-tenure-xtend-2-0';

                if (
                    isset($user->currentProgram->new_tenure_send) &&
                    $user->currentProgram->new_tenure_send &&
                    isset($user->currentProgram->new_tenure_template) &&
                    $user->currentProgram->new_tenure_template
                ) {
                    $email_template = $user->currentProgram->new_tenure_template;
                }

                $pointsvalue = 0;

                foreach ($tenurearray as $setting) {
                    if($diffyears == intval($setting['tenure_year'])) {
                        $pointsvalue = $setting['tenure_value'];
                        $newLedger = new PointsLedger;
                        $newLedger->points_value = $pointsvalue;
                        $newLedger->user_id = $user->id;
                        $newLedger->program = $user->currentProgram->id;
                        $newLedger->type = 1;
                        $newLedger->save();


                        $vars = [
                            'subject' => 'Congratulation On Your Service Anniversary',
                            'receiverfirstname' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'programname' => $user->currentProgram->name,
                            'diffYears' => $diffyears,
                            'POINTSVALUE' => $pointsvalue,
                            'POINTSNAME' => $user->currentProgram->points_name
                        ];

                        if(env('APP_ENV') == 'staging') {
                            $toemails = ['mar.herko@gmail.com', 'hq@evtmarketing.com.au'];
                        }
                        Event::fire('xtend.sendEmail', [$toemails,$ccemails, $vars, $email_template, '']);
                    }
                }
            }
        });

        // Extend all backend form usage

        Event::listen('backend.form.extendFields', function($widget) {

            // Only for the User controller
            if (!$widget->getController() instanceof UsersController) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof UserModel) {
                return;
            }

            $widget->removeField('name');
            $widget->removeField('surname');
            $widget->removeField('groups');
            $widget->removeField('block_mail');

            $widget->addFields([
                'full_name' => [
                    'label' => 'Full Name (Use This To Edit A Name)',
                    'type'    => 'text',
                ],
                'business_name' => [
                    'label' => 'Business Name (Optional)',
                    'type'    => 'text',
                    'emptyOption' => 'Not Set',
                ],
                'company_position' => [
                    'label' => 'Company Title',
                    'type'    => 'text',
                ],
                'external_reference' => [
                    'label' => 'External Reference',
                    'type'    => 'text',
                ],
            ]);

            // Add an extra birthday field
            $widget->addTabFields([
                'email_confirmed' => [
                    'label' => 'Emailed Confirmed',
                    'type'    => 'switch',
                    'span' => 'auto',
                    'tab' => 'Xtend Administration',
                ],
                't_and_c_accept' => [
                    'label' => 'T & C Accepted',
                    'type'    => 'switch',
                    'span' => 'auto',
                    'tab' => 'Xtend Administration',
                ],
                'can_buy_points' => [
                    'label' => 'User Can Buy Points',
                    'type'    => 'switch',
                    'span' => 'auto',
                    'tab' => 'Xtend Administration',
                ],
                'current_territory' => [
                    'label' => 'Current Territory',
                    'type'    => 'dropdown',
                    'span' => 'full',
                    'options' => ['AU' => 'AU', 'NZ' => 'NZ', 'UK' => 'UK'],
                    'tab' => 'Xtend Administration',
                ],
                'points_limit' => [
                    'label' => 'Maximum Points A User Can Acrue (Dollar Value)',
                    'type'    => 'number',
                    'span' => 'full',
                    'tab' => 'Xtend Administration',
                ],
                'teams' => [
                    'label'   => 'Teams',
                    'type'    => 'partial',
                    'tab' => 'Teams',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_teams.htm',
                ],
                'teams_manager' => [
                    'label'   => 'Teams Managed',
                    'type'    => 'partial',
                    'tab' => 'Teams Managed',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_teams_manager.htm',
                ],
                'programs' => [
                    'label'   => 'Programs',
                    'type'    => 'partial',
                    'tab' => 'Programs',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_programs.htm',
                ],
                'programs_manager' => [
                    'label'   => 'Programs Managed',
                    'type'    => 'partial',
                    'tab' => 'Programs Managed',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_programs_manager.htm',
                ],
                'targetingtags' => [
                    'label'   => 'Targeting Tags',
                    'type'    => 'partial',
                    'tab' => 'Targeting Tags',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_targeting_tags.htm',
                ],
                'slug' => [
                    'label' => 'Url Slug',
                    'span'  => 'full',
                    'tab'   => 'rainlab.user::lang.user.account'
                ],
                'new_birthday' => [
                    'label'   => 'Birthday',
                    'type'    => 'datepicker',
                    'mode' => 'date',
                    'tab' => 'Anniversaries',
                ],
                'today_birth_date' => [
                    'label'   => 'Birthday Flag',
                    'type'    => 'switch',
                    'tab' => 'Anniversaries',
                ],
                'updated_birth_date' => [
                    'label'   => 'Updated Birthday Flag',
                    'type'    => 'datepicker',
                    'readOnly' => 'true',
                    'tab' => 'Anniversaries',
                ],
                'new_tenure' => [
                    'label'   => 'Tenure Date',
                    'type'    => 'datepicker',
                    'mode' => 'date',
                    'tab' => 'Anniversaries',
                ],
                'today_commencement_date' => [
                    'label'   => 'Tenure Flag',
                    'type'    => 'switch',
                    'tab' => 'Anniversaries',
                ],
                'updated_commencement_date' => [
                    'label'   => 'Updated Tenure Flag',
                    'type'    => 'datepicker',
                    'readOnly' => 'true',
                    'tab' => 'Anniversaries',
                ],
                'register_form_questions_answers' => [
                    'label' => 'Register Questions',
                    'type'    => 'repeater',
                    'readOnly' => 'true',
                    'tab' => 'Registration',
                ],
                'points_json' => [
                    'label'   => 'Points Array',
                    'type' => 'partial',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_points_json.htm',
                    'readOnly' => 'true',
                    'tab' => 'codengine.awardbank::lang.PointsLedger.PointsLedgers',
                ],
                'pointsledgers' => [
                    'label'   => 'codengine.awardbank::lang.PointsLedger.PointsLedgers',
                    'type'    => 'partial',
                    'tab' => 'codengine.awardbank::lang.PointsLedger.PointsLedgers',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_pointsledgers.htm',
                ],
                'orders' => [
                    'label'   => 'codengine.awardbank::lang.Order.Orders',
                    'type'    => 'partial',
                    'tab' => 'codengine.awardbank::lang.Order.Orders',
                    'path' => '~/plugins/codengine/awardbank/controllers/users/_field_orders.htm',
                ],
                'popup_title' => [
                    'label'   => 'Pop-up Title',
                    'type'    => 'text',
                    'tab'     => 'Pop-up',
                ],
                'popup_content' => [
                    'label'   => 'Pop-up Content',
                    'type'    => 'richeditor',
                    'tab'     => 'Pop-up',
                ]
            ]);

        });

        Event::listen('backend.list.extendColumns', function($widget)
        {

            // Only for the User controller
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof \RainLab\User\Models\User) {
                return;
            }

            $widget->removeColumn('name');
            $widget->removeColumn('surname');
            $widget->removeColumn('groups');
            $widget->removeColumn('block_mail');
            $widget->removeColumn('registered');

            $widget->addColumns([
                'updated_at' =>  [
                    'label' => 'Updated At',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'date',
                ],
                'created_at' =>  [
                    'label' => 'Created At',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'date',
                    'invisible' => 'true',
                ],
                'full_name' => [
                    'label' => 'Full Name',
                    'searchable' => 'true',
                    'sortable' => 'true',
                ],
                'business_name' => [
                    'label' => 'Business Name (Optional)',
                    'type'    => 'text',
                    'searchable' => 'true',
                    'sortable' => 'true',
                ],
                'company_position' => [
                    'label' => 'Company Title',
                    'type'    => 'text',
                    'searchable' => 'true',
                    'sortable' => 'true',
                ],
                'external_reference' => [
                    'label' => 'External Reference',
                    'type'    => 'text',
                    'searchable' => 'true',
                    'sortable' => 'true',
                ],
                'avatar' => [
                    'label' => 'Avatar',
                    'relation' => 'avatar',
                    'type' => 'partial',
                    'path' => '~/plugins/codengine/awardbank/models/_avatar_partial.htm',
                    'searchable' => 'false',
                    'sortable' => 'false',
                ],
                'register_form_questions_answers' => [
                    'label' => 'Register Questions',
                    'searchable' => 'true',
                    'sortable' => 'false',
                    'invisible' => 'true',
                ],
                'email_confirmed' => [
                    'label' => 'Emailed Confirmed',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'switch',
                ],
                'company_position' => [
                    'label' => 'Company Title',
                    'type'    => 'text',
                    'searchable' => 'true',
                    'sortable' => 'true',
                ],
                'current_territory' => [
                    'label' => 'Territory',
                    'type'    => 'text',
                    'searchable' => 'true',
                    'sortable' => 'true',
                ],
                't_and_c_accept' => [
                    'label' => 'T & Cs Accepted',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'switch',
                ],
                'can_buy_points' =>  [
                    'label' => 'Can Buy Points',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'switch',
                ],
                'points_limit' =>  [
                    'label' => 'Points Limit',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'number',
                ],
                'birth_date' =>  [
                    'label' => 'Birthday',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'date',
                ],
                'today_birth_date' => [
                    'label'   => 'Birthday Flag',
                    'type'    => 'switch',
                    'searchable' => 'false',
                    'sortable' => 'true',
                ],
                'updated_birth_date' => [
                    'label'   => 'Updated Birthday Flag',
                    'type'    => 'date',
                    'searchable' => 'false',
                    'sortable' => 'true',
                ],
                'commencement_date' =>  [
                    'label' => 'Tenure Date',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'type' => 'date',
                ],
                'today_commencement_date' => [
                    'label'   => 'Tenure Flag',
                    'type'    => 'switch',
                    'searchable' => 'false',
                    'sortable' => 'true',
                ],
                'updated_commencement_date' => [
                    'label'   => 'Updated Tenure Flag',
                    'type'    => 'date',
                    'searchable' => 'false',
                    'sortable' => 'true',
                ],
            ]);

            $widget->removeColumn('name');

        });

        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            $controller->addCss('/plugins/codengine/awardbank/assets/backend.css');
        });

        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            $controller->addJs('/plugins/codengine/awardbank/assets/backend.js');
        });

        Event::listen('xtend.runbilling', function()
        {
            Log::debug('xtend.runbilling task is running');
            // generate new Xero api token
            XeroAPI::getNewAccessToken();

            $current_date = Carbon::now();
            $billingDay = $current_date->day;
            $todayBilling = Program::where('xero_renewal_day_of_month','=',$billingDay)->get();
            foreach($todayBilling as $program){
                if($program->billingcontact){
                    $program->save();
                    try {
                        $transaction = new Transaction;
                        if($program->xero_renewal_member_count <= 10){
                            $transaction->value = 10;
                        } else {
                            $transaction->value = $program->xero_renewal_member_count * $program->xero_renewal_price_plan;
                        }
                        $transaction->type = 'renewal';
                        $transaction->program_id = $program->id;
                        $transaction->billing_contact_id = $program->billingcontact->id;
                        $transaction->save();
                    } catch (\Exception $e) {
                        Log::debug('Transaction Error:: ' . $e->getMessage());
                    }
                }
            }
        });

        Event::listen('xtend.wishlist.notifications', function () {
            //Ask client::
            //1) User can add same product into the wishlist multiple times (volume will be increased which means that the user will need have more points). How do you want to handle this? Do you want to trigger the email once the user has enough points to buy 1 piece of the product or wait till the user has enough points to buy the desired amount of the product? Please consider that the user may have enough point to buy one piece but not enough to buy two.
            //2) This new functionality won't work for products that are already in the wishlist.
            //Logic explanation:
            //When the user adds a product into wishlist system will check whether the user can afford the product or not.
            //If no, product in the wishlist will be flagged as "not affordable"
            //Every hour the system will get through every user wishlist and check whether the products marked as "not affordable" are not affordable for the user
            //If yes, system will update the product flag to "affordable" - this way the system won't trigger notification until the product will be removed & added again into the wishlist. That means there's no way how can be the "affordable" flag for a product in the wishlist change from "affordable" to "not affordable".
            //

            $users = UserModel::whereNotNull('wishlist_array')
                //We can't use this `current_points` attr because it's not being updated immediately, user needs to log in first
                //->where('current_points', '>', 0)
                //->where('id', '=', 2824)
                ->get();

            if (!empty($users)) {
                foreach ($users as $user) {
                    if (is_array($user->wishlist_array) && !empty($user->wishlist_array)) {
                        foreach($user->wishlist_array as $programId => $wishlist_products) {
                            if (!empty($wishlist_products)) {
                                foreach($wishlist_products as $wishlist_product_id => $wishlist_product) {
                                    if (isset($wishlist_product['affordable'])
                                        && $wishlist_product['affordable'] === 'no') {
                                        $product = Product::find($wishlist_product_id);
                                        $program = Program::find($programId);
                                        if ($product && $program->wishlist_notifications) {
                                            $userCurrentPoints = $this->getUserCurrentPoints($user->id, $programId);
                                            if (($product->points_value * $program->scale_points_by)
                                                <= $userCurrentPoints) {
                                                //Update wishlist
                                                $updatedWishlist = $user->wishlist_array;
                                                $updatedWishlist[$programId][$wishlist_product_id]['affordable'] = 'yes';
                                                $user->wishlist_array = $updatedWishlist;
                                                //Send the notification email
                                                if ($user->save()) {
                                                    $vars = [
                                                        'receiverfirstname' => $user->name,
                                                        'programname' => $program->name,
                                                        'rewardname' => $product->name ?? 'one of the products in your wishlist.'
                                                    ];

                                                    if (in_array(env('APP_ENV'),['dev', 'staging'])) {
                                                        $toemails = ['hq@evtmarketing.com.au'];
                                                    } else {
                                                        $toemails = [$user->email];
                                                    }
                                                    $ccemails = [/*'hq@evtmarketing.com.au'*/];
                                                    $email_template = $program->wishlist_template ?? 'ase-wishlist-email-xtend-2-0';

                                                    Event::fire('xtend.sendEmail',
                                                        [
                                                            $toemails,
                                                            $ccemails,
                                                            $vars,
                                                            $email_template,
                                                            'Good news! You can now afford one of the items in your wishlist'
                                                        ]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });

        Event::listen('xtend.scorecard.imports', function() {
            try {
                $scorecardImport = ScorecardResultImport::whereIn(
                    'status',
                    [ScorecardResultImport::STATUS_PENDING, ScorecardResultImport::STATUS_IN_PROGRESS]
                )->orderBy('id', 'ASC')
                    ->first();

                //TODO - You must make sure that no other cron will run until all other in progress cron finishes
                //Otherwise the records might get override
                if ($scorecardImport) {
                    Log::debug('$scorecardImport->processing :: ' . $scorecardImport->processing);
                    if ($scorecardImport->processing !== 1) {
                        $scorecardImport->processing = 1;
                        Log::debug('Processing being set to 1');
                        $scorecardImport->save();
                        Log::debug('Processing set to 1');
                        ResultsManagement::processScorecardImportFile($scorecardImport);
                    }
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage() . ' | ' . $e->getTraceAsString());
            }
        });

        if (!empty($_GET['job']) && $_GET['job'] == 'wishlist-notifications') {
            Event::fire('xtend.wishlist.notifications');
        }

        if (!empty($_GET['job']) && $_GET['job'] == 'scorecard-imports') {
            Event::fire('xtend.scorecard.imports');
        }
    }

    private function getUserCurrentPoints($userId, $programId)
    {
        $hasABaseLine = PointsLedger::select('id','points_value')
            ->where('user_id','=',$userId)
            ->where('program_id','=',$programId)
            ->where('type','=',0)->orderBy('id','DESC')
            ->first();

        if(!$hasABaseLine){
            $points = 0;
            $startid = 0;
        } else {
            $points = $hasABaseLine->points_value;
            $startid = $hasABaseLine->id;
        }

        $additions = PointsLedger::where('id','>=',$startid)->where('user_id','=',$userId)->where('program_id','=',$programId)->where('type','=',1)->sum('points_value');
        $subtractions = PointsLedger::where('id','>=',$startid)->where('user_id','=',$userId)->where('program_id','=',$programId)->where('type','=',2)->sum('points_value');
        $inCart = PointsLedger::where('id','>=',$startid)->where('user_id','=',$userId)->where('program_id','=',$programId)->where('type','=',3)->sum('points_value');
        $outCart = PointsLedger::where('id','>=',$startid)->where('user_id','=',$userId)->where('program_id','=',$programId)->where('type','=',4)->sum('points_value');

        $cartCurrent = $inCart - $outCart;
        $currentpoints = $points + $additions - $subtractions - $cartCurrent;

        return $currentpoints;
    }

    public function registerSchedule($schedule)
    {
        if (env('APP_ENV') == 'production') {
            $schedule->call(function () {
                Event::fire('xtend.updateAnniversaryFlags', ['new_birthday']);
                $cron = new CronChecker();
                $cron->type = 'Daily - Birthdate';
                $cron->save();
                Event::fire('xtend.updateAnniversaryFlags', ['new_tenure']);
                Event::fire('xtend.sendTenureEmail');
                $cron = new CronChecker();
                $cron->type = 'Daily - Tenure';
                $cron->save();
                Event::fire('xtend.runbilling');
                $cron = new CronChecker();
                $cron->type = 'Daily - Billing';
                $cron->save();
            })->daily();
        }

        if (in_array(env('APP_ENV'),['production', 'staging'])) {
            $schedule->call(function () {
                Event::fire('xtend.scorecard.imports');
                $cron = new CronChecker();
                $cron->type = 'Running cron - xtend.scorecard.imports';
                $cron->save();
            })->everyMinute();
        }

        if (in_array(env('APP_ENV'),['production'])) {
            $schedule->call(function () {
                Event::fire('xtend.wishlist.notifications');
                $cron = new CronChecker();
                $cron->type = 'Running cron - xtend.wishlist.notifications';
                $cron->save();
            })->everyFiveMinutes();
        }

        $schedule->call(function () {
            Event::fire('xtend.runningcron');
            $cron = new CronChecker();
            $cron->type = 'Running cron';
            $cron->save();
        })->everyFiveMinutes();

        /*$schedule->call(function () {
            Event::fire('xtend.updateAnniversaryFlags', ['new_birthday']);
            Event::fire('xtend.updateAnniversaryFlags', ['new_tenure']);
            $cron = new CronChecker();
            $cron->type = 'Cron - 5 mins';
            $cron->save();
        })->everyFiveMinutes();*/

    }

    public function getRenewals()
    {
        $current_date = Carbon::now();
        $this->billingDay = $current_date->day;
        $this->todayBilling = Program::where('xero_renewal_day_of_month','=',$this->billingDay)->get();
    }


    public function onRunBilling()
    {
        $this->getRenewals();
        foreach($this->todayBilling as $program){
            if($program->billingcontact){
                $program->save();
                $transaction = new Transaction;
                if($program->xero_renewal_member_count <= 10){
                    $transaction->value = 10;
                } else {
                    $transaction->value = $program->xero_renewal_member_count * $program->xero_renewal_price_plan;
                }
                $transaction->type = 'renewal';
                $transaction->program_id = $program->id;
                $transaction->billing_contact_id = $program->billingcontact->id;
                $transaction->save();
            }
        }
    }

    /**
    Bounce down & flatten Access relations on the $model into the databse for quicker retrieval
    $model = A User Model, ideally eager loaded with relationships
     **/

    public function flattenAllAccessArray($model)
    {
        $model->current_all_teams_id = $model->teams()->pluck('id')->toArray();
        $model->current_all_regions_id = [];
        $model->current_all_programs_id = $model->programs()->pluck('id')->toArray();
        $model->current_all_orgs_id = $model->programs->map(function ($program) {
            return $program->organization->id ?? '';
        })->flatten()->toArray();
    }

    /**
    Set Defaults For The Currently Logged In User Access If Unset
    $model = A User Model, ideally eager loaded with relationships
     **/

    public function setCurrentAccess($model)
    {
        if($model->programs->count() >= 1){
            if($model->current_team_id === null){
                $model->current_team_id = $model->programs()->first()->teams()->whereHas('users', function($query) use($model){
                    $query->where('id','=',$model->id);
                })->first()->id;
            }
            /**
            if($model->programs()->first()->regions){
            $model->current_region_id = $model->programs->first()->regions->first()->id;
            }
             **/
            if($model->current_program_id === null){
                $model->current_program_id = $model->programs()->first()->id;
            }
            if($model->current_org_id === null){
                $model->current_org_id = $model->programs()->first()->organization->id;
            }
            if($model->current_territory === null){
                $model->current_territory = $model->programs()->first()->territory;
            }
        }
    }

    /**
    Bounce down & flatten Access Ownership relations on the $model into the databse for quicker retrieval
    $model = A User Model, ideally eager loaded with relationships
     **/

    public function flattenManagementArray($model)
    {
        $managementArray = [];
        $managementArray['organizations'] = [];
        if(empty($managementArray['organizations'])){
            unset($managementArray['organizations']);
        }
        $managementArray['programs'] = $model->programs_manager->mapWithKeys(function ($program) {
            return [$program->id => $program->name];
        })->toArray();
        if(empty($managementArray['programs'])){
            unset($managementArray['programs']);
        }
        $managementArray['regions'] = [];
        foreach($model->owner as $permissions){
            foreach($permissions->regions as $region){
                $managementArray['regions'][$region->id] = $region->name;
            }
        }
        if(empty($managementArray['regions'])){
            unset($managementArray['regions']);
        }
        $managementArray['teams'] = $model->teams_manager->mapWithKeys(function ($team) {
            return [$team->id => $team->name];
        })->toArray();
        if(empty($managementArray['teams'])){
            unset($managementArray['teams']);
        }
        array_filter($managementArray);
        $model->owner_array =  $managementArray;
    }

    public function dateConvert($date)
    {
        $date = new Carbon($date);
        return $date;
    }

}
