<?php namespace Codengine\Awardbank\Models;
use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\Mandrill\Message;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use Codengine\Awardbank\Models\Point;
use Codengine\Awardbank\Models\ActivityFeed;
use Codengine\Awardbank\Models\PointAllocation;
use Illuminate\Support\Facades\Log;
use Model;
use Mail;
use Db;

/**
 * Model
 */
class Thankyou extends Model
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
    public $table = 'codengine_awardbank_thankyous';

    public function afterCreate(){

        $this->emailThankyou();
        $this->activityFeed();
    }

    public function emailThankyou(){

        $roles = [1,3,4];

        $backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();

        $toemail = $this->reciever->email;
        //$toemail = 'hq@xtendsystem.com';
        $program = Program::find($this->program_id);
        $programname = $program->name;
        $programpointsname =  $this->program_points_name;
        $program_image_url = $this->program_image_path;

        // TO-DO Need to fix query
        $userpointsvalue = $this->reciever->current_points;

        $senderfullname = $this->sender_fullname;
        $receiverfullname = $this->receiver_fullname;
        $thankyoumessage = $this->thankyou_text;
        $programslug = $program->slug;
        $title = 'You received a thankyou!';

        if ($program->new_thankyou_template != null && $program->new_thankyou_template != '' && $program->new_thankyou_template != 1){
            $template = $program->new_thankyou_template;
        } else {
            $template = 'xtenddefault-thankyou-2-0';
        }

        if ($program->new_thankyou_send == true) {
            $vars = [
                'programname' => $programname,
                'pointsname' => $programpointsname,
                'pointsvalue' => $userpointsvalue,
                'programimageurl' => $program_image_url,
                'title' => $title,
                'senderfullname' => $senderfullname,
                'programslug' => $programslug,
                'message' => $thankyoumessage,
                'receiverfullname' => $receiverfullname,
            ];

            $template = new Template($template);
            $message = new Message();
            $message->setSubject($title);
            $message->setFromEmail('noreply@xtendsystem.com');
            $message->setMergeVars($vars);

            $recipient = new Recipient($toemail, null, Recipient\Type::TO);
            $recipient->setMergeVars($vars);
            $message->addRecipient($recipient);

            MandrillTemplateFacade::send($template, $message);
        }
    }

    public function activityFeed(){

        $activityFeed = new ActivityFeed;
        $activityFeed->program_id = $this->program_id;
        $activityFeed->user_id = $this->sender_id;
        $activityFeed->icon_image = $this->sender_thumb_path;
        $activityFeed->icon_link = '/profiles/'.$this->sender_id;
        $activityFeed->html = '<a href="/profile/'.$this->sender_id.'" class="user">'.$this->sender_fullname.'</a> thanked <a href="/profile/'.$this->receiver_id.'" class="user">'.$this->receiver_fullname.'</a> for their work.';
        $activityFeed->save();

    }
}
