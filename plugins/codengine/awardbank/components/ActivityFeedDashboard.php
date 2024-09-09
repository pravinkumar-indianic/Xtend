<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\ActivityFeed;
use Storage;
use Config;
use Auth;


class ActivityFeedDashboard extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $activityfeed;
    public $moduleEnabled;

    public function componentDetails()
    {
        return [
            'name' => 'Activity Feed Dashboard Plugin',
            'description' => 'Shows The Activity Feed On The Dashboard',
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

        if($this->user){

            $this->moduleEnabled = $this->user->currentProgram ? $this->user->currentProgram->module_allow_activity_feed : false;

        }

    }

    public function onRun()
    {

        if($this->moduleEnabled == true){

            $this->activityfeed = ActivityFeed::
                where('program_id','=', $this->user->current_program_id)
                ->orderBy('updated_at','desc')
                ->limit(10)
                ->get();
        }
    }

}
