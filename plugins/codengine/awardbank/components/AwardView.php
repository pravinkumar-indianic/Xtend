<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Nomination;
use Codengine\Awardbank\Models\Prize;
use Codengine\Awardbank\Models\Award;
use Codengine\Awardbank\Models\Vote;
use Codengine\Awardbank\Models\Team;
use \Cms\Classes\ComponentBase;
use RainLab\User\Models\User;
use Carbon\Carbon;
use Session;
use Event;
use Auth;

class AwardView extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $award;
    private $moduleEnabled;
    private $nominations;

    public $component;
    public $component2;
    public $html1;

    public function componentDetails()
    {
        return [
            'name' => 'Award View',
            'description' => 'View A Award',
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
            $this->user = $this->user->load('currentProgram');
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_awards'], true);
        }

        $this->component = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'fileUploader',
            ['deferredBinding' => true]
        );

        $this->component->bindModel('nomination_image', new Nomination);
        $data = Session::all();
        $key = $data['_token'];
        $this->component->sessionKey = $key;

        $this->component2 = $this->addComponent(
            'Responsiv\Uploader\Components\FileUploader',
            'fileUploader2',
            ['deferredBinding' => true]
        );
        $this->component2->bindModel('nomination_file', new Nomination);
        $key = $data['_token'];
        $this->component2->sessionKey = $key;
    }

    public function onRun()
    {
        if($this->moduleEnabled){
            $this->addJs('/plugins/codengine/awardbank/assets/js/AwardView151019.js');
            $this->coreLoadSequence();
        }
    }

    public function coreLoadSequence()
    {
        $this->getAward();
        $this->awardFactory();
        $this->generateHtml();
    }

    public function getAward()
    {
        $slug = $this->param('slug');
        $this->award = Award::where('slug','=', $slug)->first();
        if($this->award == null){
            $this->award = Award::find($slug);
        }
        if($this->Award){
            $this->award = $this->Award->load('alias','managers');
        }
    }

    public function awardFactory()
    {
        $this->award->managed = false;
        $this->award->managed = $this->user->currentProgram->checkIfManager($this->user);
        $managerusers = $this->award->managers()->pluck('id')->toArray();
        if(in_array($this->user->id,$managerusers)){
            $this->award->managed = true;
        }

        $allteams = $this->user->current_all_teams_id;

        $viewingteams = $this->award->viewingteams()->pluck('id')->toArray();
        $viewingteamsavail = array_intersect($allteams,$viewingteams);

        $nominatingteams = $this->award->nominationteams()->pluck('id')->toArray();
        $nominatingteamsavail = array_intersect($nominatingteams,$viewingteams);

        $votingteams = $this->award->votingteams()->pluck('id')->toArray();
        $votingteamsavail = array_intersect($votingteams,$viewingteams);

        $viewrelationallowed = false;
        if((count($viewingteamsavail) >= 1) || $this->award->awardallprogramview == true){
            $viewrelationallowed = true;
        }

        $nominaterelationallowed = false;
        if((count($nominatingteamsavail) >= 1) || $this->award->awardallprogramnominate == true){
            $nominaterelationallowed = true;
        }

        $voterelationallowed = false;
        if((count($votingteamsavail) >= 1) || $this->award->awardallprogramvote == true){
            $voterelationallowed = true;
        }

        $this->award->viewallowed = $this->award->managed;
        $this->award->nominateallowed = false;
        $this->award->voteallowed = false;

        $this->award->timezone = $this->user->currentProgram->timezone;
        $now = new Carbon;

        if( $this->award->award_open_at <= $now
            && $this->award->award_close_at >= $now
            && $viewrelationallowed == true){
            $this->award->viewallowed = true;
        }

        if($this->award->hide_nominations_tab == true
            && $this->award->nominations_open_at <= $now
            && $this->award->nominations_closed_at >= $now
            && $nominaterelationallowed == true){
            $this->award->nominateallowed = true;
        }

        if($this->award->hide_voting_tab == true
            && $this->award->votes_open_at <= $now
            && $this->award->votes_close_at >= $now
            && $voterelationallowed == true) {
            $this->award->voteallowed = true;
        }

        if($this->award->nomination_type == 0){
            if($this->award->awardallprogramnominateable == true){
                $this->award->potentialnominees = $this->award->program->users;
            } else {
                $this->award->potentialnominees = $this->award->nominatableusers;
            }
            foreach($this->award->paired_award as $award){
                if($award->awardallprogramnominateable == true){
                    $this->award->potentialnominees = $this->award->potentialnominees->merge($award->program->users);
                } else {
                    $this->award->potentialnominees = $this->award->potentialnominees->merge($award->nominatableusers);
                }
            }
            $this->award->potentialnominees->each(function($nominee){
                if ($nominee->company_position != null && $nominee->company_position != ''){
                    $nominee->safename = $nominee->full_name.' - '.$nominee->company_position;
                } else {
                    $nominee->safename = $nominee->full_name;
                }
                return $nominee;
            });
        } elseif($this->award->nomination_type == 1){
            if($this->award->awardallprogramnominateable == true){
                $this->award->potentialnominees = $this->user->currentProgram->teams;
            } else {
                $this->award->potentialnominees = $this->award->nominatableteams;
            }
            foreach($this->award->paired_award as $award){
                if($award->awardallprogramnominateable == true){
                    $this->award->potentialnominees->merge($award->program->teams);
                } else {
                    $this->award->potentialnominees->merge($award->nominatableteams);
                }
            }
            $this->award->potentialnominees->each(function($nominee){
                $nominee->safename = $nominee->name;
                return $nominee;
            });
        }

        $this->award->prizes->each(function($prize){
            $prize->points_name = 'Points';
            if($this->award->program){
                $prize->points_name = $this->award->program->points_name;
            }
            $prize->color = $this->award->secondary_color;
            return $prize;
        });

        if($this->award->nomination_type == 0){
            $this->nominations = $this->award->nominations()->where('approved_at','!=', null)->get()->groupBy('nominated_user_id');
        } elseif($this->award->nomination_type == 1){
            $this->nominations = $this->award->nominations()->where('approved_at','!=', null)->get()->groupBy('team_id');
        } else {
            $this->nominations = $this->award->nominations()->where('approved_at','!=', null)->get()->groupBy('id');
        }
        $array = [];
        foreach($this->nominations as $group){
            $nomination = [];
            $nomination['count'] = 0;
            $nomination['total_votes'] = 0;
            $nomination['files'] = [];
            $nomination['images'] = [];
            foreach($group as $key => $value){
                $nomination['id'] = $value->id;
                $nomination['nominee_full_name'] = $value->nominee_full_name;
                $nomination['count'] = $nomination['count'] + 1;
                $nomination['total_votes'] = $nomination['total_votes'] + $value->votes->count();
                if($value->nomination_file){
                    $nomination['files'][] = $value->nomination_file->getPath();
                }
                if($value->nomination_image){
                    $nomination['images'][] = $value->nomination_image->getPath();
                }
            }
            $nomination = collect($nomination);
            $array[] = $nomination;
        };
        $array = collect($array);
        $this->nominations = $array;

        // Sort nominees in ASC order
        if(isset($this->award) && isset($this->award->potentialnominees)) {
            $this->award->potentialnominees = collect($this->award->potentialnominees)->sortBy('safename')->toArray();
        }
    }

    public function generateHtml()
    {
        $this->html1 = $this->renderPartial('@awardview',
            [
                'award' => $this->award,
                'nominations' => $this->nominations,
            ]
        );
    }

    public function onShowWinners()
    {
        $prize = Prize::find(post('id'));
        if($prize){
            $result['updatesucess'] = "Congratulations to the following winners.";
            $i = 0;
            $length = $prize->userwinners->count() - 1;
            foreach($prize->userwinners as $winner){
                if($i == 0){
                    $result['updatesucess'] .= ' Users: ';
                }
                $result['updatesucess'] .= $winner->full_name;
                if($i < $length){
                    $result['updatesucess'] .= ', ';
                } else {
                    $result['updatesucess'] .= '.';
                }
                $i++;
            }
            $i = 0;
            $length = $prize->teamwinners->count() - 1;
            foreach($prize->teamwinners as $winner){
                if($i == 0){
                    $result['updatesucess'] .= ' Teams: ';
                }
                $result['updatesucess'] .= $winner->name;
                if($i < $length){
                    $result['updatesucess'] .= ', ';
                } else {
                    $result['updatesucess'] .= '.';
                }
                $i++;
            }
            return $result;
        }
    }

    public function onCreateNomination()
    {
        $this->getAward();
        $data = Session::all();
        $sessionkey = $data['_token'];
        $nomination = new Nomination();
        $nomination->award_id = $this->award->id;
        $nomination->created_user_id = $this->user->id;
        if($this->award->nomination_type == 2){
            $nomination->nominated_user_id = $this->user->id;
        } elseif ($this->award->nomination_type == 1){
            $team = Team::find(post('nominee'));
            if($team != null){
                $nomination->team_id = post('nominee');
            } else {
                $result['manualerror'] = 'No Team Found To Match Nomination.';
                return $result;
            }
        } else {
            $nominateduser = User::find(post('nominee'));
            if($nominateduser != null){
                $nomination->nominated_user_id = post('nominee');
            } else {
                $result['manualerror'] = 'No User Found To Match Nomination.';
                return $result;
            }
        }
        $questionAnswers = [];
        foreach(post() as $key => $value){
            if($key == '_session_key' || $key == '_token' || $key == "award_id" || $key == 'nominee' || $key == '_uploader'){
                continue;
            } else {
                $questionAnswers[$key] = $value;
            }
        }
        $nomination->questions_answers = $questionAnswers;
        $nomination->program_id = $this->user->currentProgram->id;
        $nomination->submit_team_id = $this->user->current_team_id;
        if($this->award->nominations_approval_required == false){
            $nomination->approved_at = Carbon::now();
            $nomination->approved_user_id = $this->award->created_user_id;
        }
        $nomination->save(null, $sessionkey);
        $this->coreLoadSequence();
        $result['html']['#html1target'] = $this->html1;
        $result['updatesucess'] = 'Nomination succesfully submitted.';
        return $result;
    }

    public function onAddVote()
    {
        $nomination = Nomination::find(post('id'));
        $this->getAward();
        if($nomination){
            $vote = new Vote();
            $vote->nomination_id = $nomination->id;
            $vote->voter_id = $this->user->id;
            $vote->program_id = $this->award->program_id;
            $vote->team_id = $this->user->current_team_id;
            $vote->voter_full_name = $this->user->full_name;
            $vote->award_id = $this->award->id;
            $vote->save();
            $this->coreLoadSequence();
            $result['html']['#html1target'] = $this->html1;
            $result['updatesucess'] = 'Vote succesfully submitted.';
            return $result;
        } else {
            $result['manualerror'] = 'No Nomination Found To Assign Vote.';
        }
    }
}
