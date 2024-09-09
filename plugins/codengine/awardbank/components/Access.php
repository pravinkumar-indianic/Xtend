<?php namespace Codengine\Awardbank\Components;

use Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Organization;
use Codengine\Awardbank\Models\ActivityFeed;
use Codengine\Awardbank\Models\Transaction;
use Codengine\Awardbank\Models\Point;
use RainLab\User\Models\User;
use Auth;
use Session;
use Redirect;
use DB;
use System\Helpers\DateTime;
use Carbon\Carbon;
use Mail;


class Access extends ComponentBase
{

    public $user;
    public $teams = [];
    public $team;
    public $currentTeamID;
    public $regions = [];
    public $region;
    public $currentRegionID;
    public $programs = [];
    public $program;
    public $currentProgramID;
    public $organizations = [];
    public $organization;
    public $currentOrganizationID;
    public $metaarea;
    public $manager = false;
    public $managedteams = null;
    public $managedregions = null;
    public $managedprograms = null;
    public $managedorganizations = null;
    public $isbirthday = false;


    public function componentDetails()
    {
        return [
            'name'        => 'Access',
            'description' => 'This Component Passes and Sets Information About The Current Access Level'
        ];
    }

    public function defineProperties()
    {
        return [

            'metaarea' => [
                'title' => 'Wrap Component In The Container',
                'type'=> 'dropdown',
                'options'     => [
                    '0' => 'Dashboard',
                    '1' => 'Rewards',
                    '2' => 'Posts',
                    '3' => 'Awards',
                    '4' => 'Results',
                    '5' => 'Profiles',
                ],
            ],
        ];
    }

    public function init()
    {
        $this->user = Auth::getUser();
        $this->metaarea = $this->property('metaarea');
    }

    public function onRun()
    {
        if($this->user){
            $this->addJs('/plugins/codengine/awardbank/assets/js/access-component.js',['component' => 'test']);
            $this->checkBounceDownRelation();
            $this->setUserRelationship();
            $this->setCurrentAccess();
            $this->setManagementVariables();
            //$this->setVars();

            if(!empty($this->program->slider_images) && $this->program->slider_images->count() >= 1){
                $this->addCss('/plugins/codengine/awardbank/assets/slick-1.8.0/slick/slick.css');
                $this->addCss('/plugins/codengine/awardbank/assets/slick-1.8.0/slick/slick-theme.css');
                $this->addJs('/plugins/codengine/awardbank/assets/slick-1.8.0/slick/slick.js');
            }
        }
    }

    /**
        Check if Current user has Access arrays set correctly. If not, re-save to run the bounceDown Function On Model
    **/

    public function checkBounceDownRelation()
    {
        if(
            $this->user->current_all_teams_id === null
            || $this->user->current_all_regions_id === null
            || $this->user->current_all_programs_id === null
            || $this->user->current_all_orgs_id === null
        )
        {
            $this->user->save();
        }
    }

    /**
        Fetch Array Of Models For All the Users Current Teams,Regions,Programs etc.
    **/

    public function setUserRelationship(){
        $this->teams = Team::whereIn('id', $this->user->current_all_teams_id)->get();
        $this->regions = Region::whereIn('id', $this->user->current_all_regions_id)->get();
        $this->programs = Program::whereIn('id', $this->user->current_all_programs_id)->get();
        $this->organizations = Organization::whereIn('id', $this->user->current_all_orgs_id)->get();
    }

    /**
        Set the components Current Access Values based on the user
    **/

    public function setCurrentAccess()
    {
        $this->currentAccessRetreive('current_team_id','team','teams','currentTeamID');
        $this->currentAccessRetreive('current_program_id','program','programs','currentProgramID');
        //$this->currentAccessRetreive('current_region_id','region','regions','currentRegionID');
        $this->currentAccessRetreive('current_org_id','organization','organizations','currentOrganizationID');
    }

    /**
        Get The Users Current Seeting For Team,Region,Program,Org
        $field = name of DB storage field to check
        $singlevar = singluar name of the relations i.e. team
        $pluralvar = plural name of the relationship i.e. teams
        $componentvar = name of the components singluar value for current id i.e. currenTeamId
    **/

    public function currentAccessRetreive($field,$singlevar,$pluralvar,$componentvar)
    {
        if($this->user->$field != null){
            $this->$singlevar = $this->$pluralvar->find($this->user->$field);
        }
        if($this->$singlevar == null){
            $this->$singlevar = $this->$pluralvar->first();
        }
        $this->$componentvar = $this->$singlevar->id ?? '';
        $this->user->$field = $this->$componentvar;
    }

    /**
        Set the components management arrays based on the user
    **/

    public function setManagementVariables()
    {
        $getTargetArray = $this->user->getTargetArray();
        $managementArray = $this->user->owner_array;
        if(is_array($managementArray)){
            foreach($managementArray as $value){
                foreach($value as $output){
                   $this->manager = true;
                }
            }
        }
        if($this->manager == true){
            $array = $getTargetArray;
            if(isset($array['teams']))
                $this->managedteams = $array['teams'];
            if(isset($array['regions']))
                $this->managedregions = $array['regions'];
            if(isset($array['programs']))
                $this->managedprograms = $array['programs'];
            if(isset($array['organizations']))
                $this->managedorganizations = $array['organizations'];
        }
    }

    public function setVars(){

        /**

        $activityFeed = ActivityFeed::where('program_id','=', $this->currentProgramID)->where('type','=','tenure')->whereDate('created_at', Carbon::today())->count();

        $now = DateTime::makeCarbon(now());

        if($this->program->celebrate_tenure == true){

            if($activityFeed == 0){

                $tenures = Collect($this->program->tenure_array);

                $tenures = $tenures->pluck('tenure_year')->toArray();

                //dump($tenures);

                //$todayUsers = User::whereRaw('DAYOFYEAR(curdate()) = DAYOFYEAR(DATE_ADD(commencement_date, INTERVAL (YEAR(NOW()) - YEAR(commencement_date)) YEAR))')->get();

                $todayUsers = User::whereRaw('DAYOFYEAR(curdate()) = DAYOFYEAR(DATE_ADD(commencement_date, INTERVAL (YEAR(NOW()) - YEAR(commencement_date)) YEAR))')->where('current_program_id','=', $this->user->id)->get();

                //dump($todayUsers);

                foreach($todayUsers as $user){

                    $commenceDate = DateTime::makeCarbon($user->commencement_date);

                    $diffYears = \Carbon\Carbon::now()->diffInYears($user->commencement_date);

                    //dump($diffYears+1);

                   // $diffYears = $diffYears;

                    //dump($diffYears);

                    if(in_array($diffYears,$tenures)){

                        $feed = new ActivityFeed;
                        $html = 'Congratulations to '. $user->full_name .' for their '. $diffYears .' year work anniversary!';

                        if($this->program->anniversary_prize_value >= 1){
                            $html .= ' They have recieved '. $this->program->anniversary_prize_value * $this->program->scale_points_by .' '. $this->program->points_name.' for achieving this milestone.';
                        }

                        $feed->html = $html;
                        $feed->type = 'tenure';
                        $feed->user_id = $user->id;
                        $feed->program_id = $this->currentProgramID;
                        $feed->region_id = $this->currentRegionID;
                        $feed->team_id = $this->currentTeamID;
                        $feed->save();

                        if($this->program->new_tenure_template != null && $this->program->new_tenure_template != '' && $this->program->new_tenure_template != 1){
                            $template = $this->program->new_tenure_template;
                        } else {
                            $template = 'xtenddefault-tenure';
                        }

                        $programname = $this->program->name;
                        $programpointsname = $this->program->points_name;
                        $userpointsvalue = Point::where('spent', '=', 0)
                        ->whereHas('allocations', function($query) use ($user) {
                            $query->where('pointallocatable_type', 'user')
                                ->where('pointallocatable_id', $user->id)
                                ->where('current_allocation', 1);
                        })
                        ->count();
                        $userpointsvalue = $userpointsvalue * $this->program->scale_points_by;
                        if ($this->program->login_image){
                            $program_image_url = $this->program->login_image->path;
                        } else {
                            $program_image_url = null;
                        }

                        if($this->program->new_tenure_send == true){

                            $roles = [1,3,4];
                            $backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();
                            $toemail = $this->user->email;
                            //$toemail = 'hq@xtendsystem.com';

                            $receiverfullname = $user->full_name;

                            Mail::send('blank',[], function($message) use ($toemail, $backendUsers, $programname, $programpointsname, $userpointsvalue, $program_image_url, $receiverfullname, $diffYears, $template){
                                $message->to($toemail);
                                $message->cc($backendUsers);
                                $message->subject(null);
                                //$message->from('noreplay@xtendsystem.com', $name = 'Xtend System');
                                $message->getSwiftMessage()->getHeaders()->addTextHeader('X-MC-MergeVars', json_encode(
                                    [
                                        'programname' => $programname,
                                        'pointsname' => $programpointsname,
                                        'pointsvalue' => $userpointsvalue,
                                        'programimageurl' => $program_image_url,
                                        'receiverfullname' => $receiverfullname,
                                        'diffyears' => $diffYears

                                    ]
                                ));
                                $message->getSwiftMessage()->getHeaders()->addTextHeader('X-MC-Template', $template);


                            });
                        }
                    }
                }
            }
        }

        if($this->program->celebrate_birthdays == true){

            $timestamptruth = $this->isValidTimestamp($this->user->birth_date);

            if($this->user->birth_date != null &&  $timestamptruth == true){
                $dob = Carbon::createFromFormat('Y-m-d H:i:s', $this->user->birth_date);
                $this->isbirthday = $dob->isBirthday();

                if($this->program->new_birthday_template != null && $this->program->new_birthday_template != '' && $this->program->new_birthday_template != 1){
                    $template = $this->program->new_birthday_template;
                } else {
                    $template = 'xtenddefault-birthday';
                }

                $programname = $this->program->name;
                $programpointsname = $this->program->points_name;
                $userpointsvalue = Point::where('spent', '=', 0)
                ->whereHas('allocations', function($query) {
                    $query->where('pointallocatable_type', 'user')
                        ->where('pointallocatable_id', $this->user->id)
                        ->where('current_allocation', 1);
                })
                ->count();
                $userpointsvalue = $userpointsvalue * $this->program->scale_points_by;
                if ($this->program->login_image){
                    $program_image_url = $this->program->login_image->path;
                } else {
                    $program_image_url = null;
                }

                /**

                if($this->program->new_birthday_send == true){

                    $roles = [1,3,4];
                    $backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();
                    $toemail = 'matthewcowley1@gmail.com';
                    $receiverfullname = $this->user->full_name;

                    Mail::send('blank',[], function($message) use ($toemail, $backendUsers, $programname, $programpointsname, $userpointsvalue, $program_image_url, $receiverfullname, $template){
                        $message->to($toemail);
                        $message->cc($backendUsers);
                        $message->subject(null);
                        //$message->from('noreplay@xtendsystem.com', $name = 'Xtend System');
                        $message->getSwiftMessage()->getHeaders()->addTextHeader('X-MC-MergeVars', json_encode(
                            [
                                'programname' => $programname,
                                'pointsname' => $programpointsname,
                                'pointsvalue' => $userpointsvalue,
                                'programimageurl' => $program_image_url,
                                'receiverfullname' => $receiverfullname,

                            ]
                        ));
                        $message->getSwiftMessage()->getHeaders()->addTextHeader('X-MC-Template', $template);


                    });
                }


            }

        }
           **/
    }

    function isValidTimestamp($format)
    {
        if (($timestamp = strtotime($format)) !== false) {
            return true;
        } else {
           return false;
        }
    }



    public function onTeamUpdate()
    {
        $teamID = post('teamID');
        $this->user = Auth::getUser();
        $this->user->current_team_id = $teamID;
        $this->user->current_region_id = null;
        $this->user->current_program_id = null;
        $this->user->current_org_id = null;
        $this->user->save();
        Redirect::refresh();
    }


    public function onProgramUpdate()
    {
        $programID = post('programID');
        $programsarray = $this->user->programs->pluck('id')->toArray();
        if(in_array($programID,$programsarray)){
            $program = Program::find($programID);
            if($program){
                $team = $program->teams()->whereHas('users', function($query){
                    $query->where('id','=',$this->user->id);
                })->first();
                $this->user->current_team_id = $team->id;
                $this->user->current_region_id = null;
                $this->user->current_program_id = post('programID');
                $this->user->current_org_id = $program->organization->id;
                $this->user->save();
                Redirect::refresh();
            }
        }
    }

    function calculate_minus_year_b4($minus) {

        date_default_timezone_set('Australia/Sydney');
        $now = time(); // Use current time.
        $month = date('m', $now);
        $day = date('d', $now) - 1;
        $year = date('Y', $now) - $minus;
        $plus_one_year = strtotime("$year-$month-$day"); // Use ISO 8601 standard.
        return DateTime::makeCarbon($plus_one_year);

    }

    function calculate_minus_year_af($minus) {
        date_default_timezone_set('Australia/Sydney');
        $now = time(); // Use current time.
        $month = date('m', $now);
        $day = date('d', $now) + 1;
        $year = date('Y', $now) - $minus;
        $plus_one_year = strtotime("$year-$month-$day"); // Use ISO 8601 standard.
        return DateTime::makeCarbon($plus_one_year);
    }

    function onAcceptTandC(){
        $this->user = Auth::getUser();
        $this->user->t_and_c_accept = true;
        $this->user->save();
    }

    public function onResendActivate(){
        $this->user = Auth::getUser();
        $this->user->sendEmailActivation();
    }
}
