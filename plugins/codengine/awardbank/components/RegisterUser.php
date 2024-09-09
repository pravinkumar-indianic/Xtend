<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\Models\Team;
use Storage;
use Config;
use Auth;


class RegisterUser extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $program;
    public $slug;
    public $regions = [];
    public $teams = [];

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

        $this->slug = $this->param('slug');
        $this->program = Program::where('slug','=', $this->slug)->where('registration_open','=',true)->with('regions.teams')->first();

        if($this->program != null){

            foreach($this->program->regions as $region){

                $this->regions[] = $region;

                foreach($region->teams as $team){

                    $this->teams[] = $team;

                }

            }
        }

    }

    public function onRun()
    {



    }

    public function onCreateRegisteredUser()
    {


        $user = Auth::register([
            'name' => post('firstname'),
            'surname' => post('surname'),
            'email' => post('email'),
            'password' => post('password'),
            'password_confirmation' => post('password'),
        ], true);

        $teams = collect($this->teams);
        $regions = collect($this->regions);

        $team = $teams->filter(function($item) {
            return $item->name == post('teamname');
        })->first();

        $region = $regions->filter(function($item) {
            return $item->name == post('regionname');
        })->first();

        if($team != null){

            $user->teams()->add($team);

        } else {

            $team = new Team;
            $team->name = post('teamname');
            $team->description = 'User Registration Created Team';
            $team->primary_color = '#3498db';
            $team->secondary_color = '#f1c40f';
            $team->save();            

            if($region != null){ 
                
                $team->regions()->add($region);
                $user->teams()->add($team);

            } else {

                $region = new Region;
                $region->name = post('regionname');
                $region->description = 'User Registration Created Region';
                $region->primary_color = '#3498db';
                $region->secondary_color = '#f1c40f';
                $region->save();    

                $this->program->regions()->add($region);
                $team->regions()->add($region);
                $user->teams()->add($team);
            }        

        }

        $user->save();

        $user->t_and_c_accept = true;

        $user->company_position = post('position');

        $user->phone_number = post('phonenumber');

        $questionAnswers = [];

        foreach(post() as $key => $value){
            if(
                $key == '_session_key' || 
                $key == '_token' || 
                $key == 'email' ||
                $key == 'firstname' ||
                $key == 'surname' ||
                $key == 'password' ||
                $key == 'position' ||
                $key == 'phonenumber' ||
                $key == 'regionname' ||
                $key == 'teamname' ||    
                $key == 'tandcaccept'            
            ){
                continue;
            } else {
                $questionAnswers[$key] = $value;
            }
        }

        $user->register_form_questions_answers = $questionAnswers;

        $user->save();

        Auth::login($user);

        $result['slug'] = $user->slug;

        return $result;

    }

}
