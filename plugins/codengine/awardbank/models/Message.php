<?php namespace Codengine\Awardbank\Models;
use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use Codengine\Awardbank\Models\Point;
use Codengine\Awardbank\Models\ActivityFeed;
use Model;
use Mail;
use Db;

/**
 * Model
 */
class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sortable;

    protected $dates = ['deleted_at'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [

        'sender' => ['Rainlab\User\Models\User', 'key' => 'sender_id', 'otherKey' => 'id'],
        'reciever' => ['Rainlab\User\Models\User', 'key' => 'receiver_id', 'otherKey' => 'id'],
        //'program' =>  ['Rainlab\User\Models\User','table' => 'codengine_awardbank_u_t'],

    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_messages';

    public function afterCreate(){

        //$this->emailMessage;
        //$this->activityFeed();
    }

    public function emailMessage(){

        $roles = [1,3];

        $backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();

        $toemail = $this->reciever->email;
        //$toemail = 'hq@xtendsystem.com';
        $programname = $this->program_name;
        $programpointsname =  $this->program_points_name;
        $program_image_url = $this->program_image_path;
        $userpointsvalue = Point::where('spent', '=', 0)
        ->whereHas('allocations', function($query) {
            $query->where('pointallocatable_type', 'user')
                ->where('pointallocatable_id', $this->receiver_id)
                ->where('current_allocation', 1);
        })
        ->count();

        $title = 'Well done '.$this->receiver_fullname.'. Your work has been recognized';
        $html = '';
        $html .= '<p>'.$this->sender_fullname.' has recognized the work you have done in the '.$programname.' Program</p>';
        $html .= '<p>'.$this->sender_fullname.' had the following to say about your work:</p>';
        $html .= '<p><i>"'.$this->thankyou_text.'"</i></p>';
        $html .= '<p>You can log into Xtend and check your colleagues profiles to recognize their work.</p>';
        $html .= '<p>Happy Rewards</p>';
        $html .= '<p>Xtend</p>';

        $template = new Template('xtenddefault');
        $vars = [
            'programname' => $programname,
            'pointsname' => $programpointsname,
            'pointsvalue' => $userpointsvalue,
            'programimageurl' => $program_image_url,
            'title' => $title,
            'body'=> $html,
        ];

        $message = new \Addgod\MandrillTemplate\Mandrill\Message();
        $message->setSubject($title);

        $message->setFromEmail('noreply@xtendsystem.com');
        $message->setFromName('Xtend System');
        $message->setMergeVars($vars);

        $recipient = new Recipient($toemail, null, Recipient\Type::TO);
        $recipient->setMergeVars($vars);
        $message->addRecipient($recipient);

        MandrillTemplateFacade::send($template, $message);
    }

    public function activityFeed(){

        $activityFeed = new ActivityFeed;
        $activityFeed->program_id = $this->program_id;
        $activityFeed->user_id = $this->sender_id;
        $activityFeed->icon_image = $this->sender_thumb_path;
        $activityFeed->icon_link = '/profiles/'.$this->sender_id;
        $activityFeed->html = '<a href="/profile/'.$this->sender_id.'" class="user">'.$this->sender_fullname.'</a> thanked <a href="/profiles/'.$this->receiver_id.'" class="user">'.$this->receiver_fullname.'</a> for their work.';
        $activityFeed->save();

    }
}
