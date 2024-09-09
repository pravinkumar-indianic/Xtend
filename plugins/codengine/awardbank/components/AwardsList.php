<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Award;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Event;
use Carbon\Carbon;

class AwardsList extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $awards;
    private $moduleEnabled;
    public $html1;

    public function componentDetails()
    {
        return [
            'name' => 'Awards List',
            'description' => 'List Of All Awards',
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
            $this->user = $this->user->load('currentProgram','currentProgram.posts');
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_awards'], true);
        }   
    }

    public function onRun()
    {
        if($this->moduleEnabled == true){
            $this->coreLoadSequence();
        }
    }

    public function coreLoadSequence()
    {
        $this->getModels();
        $this->factory();
        $this->generateHtml();      
    }

    public function getModels(){
        $this->awards = Award::where('program_id','=',$this->user->current_program_id)
        ->whereDate('award_close_at','>=', new Carbon)
        ->whereDate('award_open_at','<=', new Carbon)
        ->where(function($query){
            $query->where('awardallprogramview','=',true)
            ->orWhereHas('viewingteams',function($query){
                $query->whereIn('id',$this->user->current_all_teams_id);
            });
        })
        ->orWhere(function($query){
            $query->where('program_id','=',$this->user->current_program_id)
            ->where(function($query){
                $query->whereHas('managers',function($query){
                    $query->where('id','=',$this->user->id);
                })->orWhereHas('nominationsmanagers',function($query){
                    $query->where('id','=',$this->user->id);
                })->orWhereHas('winnersmanagers',function($query){
                    $query->where('id','=',$this->user->id);
                });
            });
        })
        ->orderBy('codengine_awardbank_awards.updated_at','desc')
        ->get();        
    }

    public function factory()
    {

    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@awardslist',
            [
                'awards' => $this->awards,
            ]
        );  
    }

}
