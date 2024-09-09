<?php namespace Codengine\Awardbank\Components;

use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Award;
use Codengine\Awardbank\Models\Thankyou;
use Codengine\Awardbank\Models\Message;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Credit;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;
use System\Helpers\DateTime;

class ProfilesList extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $container;
    public $flushtop;
    public $flushbottom;
    public $profiles;
    public $totalResults;
    public $offset;
    private $program_base_id;
    private $region_base_id;
    public $region;
    public $regions;
    private $team_base_id;
    public $teams;
    public $team;
    private $searchTerm;
    public $available_awards;
    public $featured_award_slug;

    public function componentDetails()
    {
        return [
            'name' => 'Profiles List',
            'description' => 'A List Of Profiles',
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

    }

    public function onRun()
    {

        $this->addJs('/plugins/codengine/awardbank/assets/js/thankyou.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/profiles.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/message.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/nominate.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/transfer.js');

        $this->container = $this->property('container');
        $this->flushtop = $this->property('flushtop');
        $this->flushbottom = $this->property('flushbottom');
        $this->offset = 0;
        $this->program_base_id = $this->user->current_program_id;
        if($this->param('region') != 'default'){
            $region = Region::where('slug','=',$this->param('region'))->first();
            if($region != null){
                $this->region_base_id = $region->id;
                $this->region = $region;
            } else {
                $this->region_base_id = null;
            }
        } else {
            $this->region_base_id = null;
        }
        if($this->param('team') != 'default'){
            $team = Team::where('slug','=',$this->param('team'))->first();
            if($team != null){
                $this->team_base_id = $team->id;
                $this->team = $team;
            } else {
                $this->team_base_id = null;
            }
        } else {
            $this->team_base_id = null;
        }
        $this->searchTerm = null;
        $this->setProfiles();
        $this->setRegions();
        $this->setTeams();


        /**
        $featured_award = Award::where('featured','=', 1)
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
        ->with(['owners','alias'])
        ->orderBy('codengine_awardbank_awards.updated_at','desc')
        ->first();

        $this->featured_award_slug = $featured_award->slug;

        **/
    
    }

    public function onGetAvailableAwards(){

        $awards = Award::whereHas('viewability', function($query){
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
        ->where('hide_nominations_tab','=', false)
        ->get();

        $array = [];

        foreach($awards as $award){

            $array[] = array('id' => $award->id, 'name' => $award->name);

        }

        return $array;

    }
    
    public function onCreateThankyou(){

        $receiverid = post('receiverid');
        $senderid = post('senderid');
        $thankyoutext = post('thankyoutext');                

        $thankyou = new Thankyou;
        $receiver = User::find($receiverid);
        $thankyou->receiver_id = $receiver->id;
        $thankyou->receiver_fullname = $receiver->full_name;
        if($receiver->avatar){
            $thankyou->receiver_thumb_path = $receiver->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $thankyou->receiver_thumb_path = null;
        }
        $sender = User::find($senderid);
        $thankyou->sender_id = $sender->id;
        $thankyou->sender_fullname = $sender->full_name;
        if($sender->avatar){
            $thankyou->sender_thumb_path = $sender->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $thankyou->sender_thumb_path = null;
        }
        $thankyou->thankyou_text = $thankyoutext;

        $program = Program::find($sender->current_program_id);
        $thankyou->program_id = $program->id;
        $thankyou->program_name = $program->name;
        $thankyou->program_points_name = $program->points_name;
        $thankyou->program_points_multiple_type = $program->program_markup_type;
        $thankyou->program_points_multiple_integer = $program->scale_points_by;
        if ($program->login_image){
            $thankyou->program_image_path = $program->login_image->path;
        } else {
            $thankyou->program_image_path = null;
        }

        $thankyou->save();

        $result['receivername'] = $receiver->full_name;
        $result['count'] = $receiver->getMyThankyouCount();

        return $result;        

    }

    public function onCreateMessage(){

        $receiverid = post('receiverid');
        $senderid = post('senderid');
        $messagetext = post('messagetext');                

        $message = new Message;
        $receiver = User::find($receiverid);
        $message->receiver_id = $receiver->id;
        $message->receiver_fullname = $receiver->full_name;
        if($receiver->avatar){
            $message->receiver_thumb_path = $receiver->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $message->receiver_thumb_path = null;
        }
        $sender = User::find($senderid);
        $message->sender_id = $sender->id;
        $message->sender_fullname = $sender->full_name;
        if($sender->avatar){
            $message->sender_thumb_path = $sender->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $message->sender_thumb_path = null;
        }
        $message->message_text = $messagetext;

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

        return true;        

    }

    public function onCreateNomination(){

        $receiverid = post('receiverid');
        $senderid = post('senderid');
        $awardid = post('awardid');

        $nomination = new Nomination();
        $nomination->award_id = $awardid;
        $nomination->nominated_user_id = $receiverid;
        $nomination->created_user_id = $senderid;
        $nomination->save();        

    }

    public function setProfiles(){

        if($this->team_base_id >= 1){

            $profiles = User::whereHas('teams', function($query){

                $query->where('id','=', $this->team_base_id);

            });

        } else if ($this->region_base_id >= 1){


            $profiles = User::whereHas('teams.regions', function($query){

                $query->where('id','=', $this->region_base_id);

            });

        } else {

            $profiles = User::whereHas('programs', function($query){

                $query->where('id','=', $this->user->current_program_id);

            });

        }

        if($this->searchTerm != null && $this->searchTerm != ''){

            $profiles->where('full_name','LIKE','%'.$this->searchTerm.'%');

        }

        if($this->totalResults == null){

            $this->totalResults = $profiles->count();

        }

        $this->profiles = $profiles->offset($this->offset)->limit(6)->orderBy('full_name','asc')->get();

    }

    public function setRegions(){

        $program = Program::find($this->program_base_id);
        $this->regions = $program->regions()->orderBy('name')->get();

    }

    public function setTeams(){

        $program = Program::with('teams')->find($this->program_base_id);
        $array = [];
        foreach($program->teams as $team){
            $array[] = $team;
        }
        $this->teams = collect($array)->sortBy('name');

    }

    public function onGetRegionTeams(){

        if(post('region_id') == 'all'){

            $this->program_base_id = $this->user->current_program_id;
            $this->setTeams();
            $array = [];
            foreach($this->teams as $team){
                $array[$team->id] = $team->name;
            }   
            $result['teams'] = $array;           

        } else {

            $region = Region::with('teams')->find(post('region_id'));
            $array = [];
            foreach($region->teams as $team){
                $array[$team->id] = $team->name;
            }        
            $result['teams'] = $array;
        }

        return $result;

    }

    public function onRefreshListFilter(){

        $this->user = Auth::getUser();

        if(post('region') != null || post('region') != 'all'){

            $this->region_base_id = post('region');

        } else {

            $this->region_base_id = null;

        }

        if(post('team') != null || post('team') != 'all'){

            $this->team_base_id = post('team');

        } else {

            $this->team_base_id = null;

        }

        $this->searchTerm = post('searchTerm');
        $this->totalResults = post('totalResults');
        $this->offset = post('offset');

        $this->setProfiles();

        $result['html'] = $this->renderPartial('@listouterpartial',
            [
                'profiles' => $this->profiles, 
            ]
        );

        $result['totalResults'] = $this->totalResults;

        return $result;

    }

    public function onTransferPoints() {

        $recieverid = post('recieverid');
        $senderid = post('senderid');
        $amount = post('amount');
        $public = post('public');
        $messagetext = post('messagetext');

        $credit = new Credit();
        $credit->user_id = $senderid;
        $credit->value = $amount;
        $credit->save();

        $points = \Codengine\Awardbank\Models\Point::where('credit_id','=', $credit->id)->doesntHave('allocations')->take($amount)->get();

        foreach($points as $point){

            $pointAllocation = new \Codengine\Awardbank\Models\PointAllocation;
            $pointAllocation->point_id = $point->id;
            $pointAllocation->pointallocatable_type = 'user';
            $pointAllocation->pointallocatable_id = $recieverid;
            $pointAllocation->previous_allocation_id = 0;
            $pointAllocation->allocator = 0;
            $pointAllocation->current_allocation = 1;
            $pointAllocation->save();

        }
        
        $credit->save();

        $message = new Message;
        $receiver = User::find($recieverid);
        $message->receiver_id = $receiver->id;
        $message->receiver_fullname = $receiver->full_name;
        if($receiver->avatar){
            $message->receiver_thumb_path = $receiver->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $message->receiver_thumb_path = null;
        }
        $sender = User::find($senderid);
        $message->sender_id = $sender->id;
        $message->sender_fullname = $sender->full_name;
        if($sender->avatar){
            $message->sender_thumb_path = $sender->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $message->sender_thumb_path = null;
        }

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
        $message_text = 'You have recieved a transfer of '.$amount * $program->scale_points_by.' '.$program->points_name.' from '.$sender->full_name.'.';

        if($message != '' && $message != null){
            $message_text .= 'The following message was included - '. $messagetext;
        }

        $message->message_text = $message_text;
        $message->save();

        if($public == 'public'){
            $activityFeed = new ActivityFeed;
            $activityFeed->program_id = $program->id;
            $activityFeed->user_id = $sender->id;
            if($sender->avatar){
                $activityFeed->icon_image = $sender->avatar->getThumb(100,100, ['mode' => 'crop']);
            }
            $activityFeed->icon_link = '/profiles/'.$sender->id;
            $activityFeed->html = '<a href="/profile/'.$sender->id.'" class="user">'.$sender->full_name.'</a> transferred '.$amount * $program->scale_points_by.' '.$program->points_name.' to <a href="/profiles/'.$receiver->id.'" class="user">'.$receiver->full_name.'</a>.';
            $activityFeed->save();
        }

    }

}
