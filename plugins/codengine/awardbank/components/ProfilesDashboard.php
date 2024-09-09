<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Award;
use Codengine\Awardbank\Models\Nomination;
use Codengine\Awardbank\Models\Thankyou;
use Codengine\Awardbank\Models\Message;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Credit;
use Codengine\Awardbank\Models\ActivityFeed;
use Storage;
use Config;
use Auth;


class ProfilesDashboard extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $profiles;

    public function componentDetails()
    {
        return [
            'name' => 'Profiles Dashboard Plugin',
            'description' => 'Profiles Feed On The Dashboard',
        ];
    }

    public function defineProperties()
    {
        return [
 
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

        $this->profiles = User::whereHas('programs', function($query){

                $query->where('id','=', $this->user->current_program_id);

        })->inRandomOrder()->limit(4)->orderBy('full_name','asc')->get();

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
        })->orwhereHas('owners', function($query){
            $query->whereHas('users', function($query){
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
