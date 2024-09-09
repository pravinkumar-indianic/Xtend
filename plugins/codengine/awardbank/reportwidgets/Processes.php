<?php namespace Codengine\Awardbank\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Event;
use Carbon\Carbon;
use Codengine\Awardbank\Models\Post;
use Codengine\Awardbank\Models\Award;
use Codengine\Awardbank\Models\Vote;
use RainLab\User\Models\User;

class Processes extends ReportWidgetBase
{
	public function defineProperties()
	{
	    return [

	    ];
	}

    public function render()
    {
        return $this->makePartial('widget');
    }
    public function onProcessPosts()
    {
        try {
            $posts = Post::all();
            $i = 1;
            foreach($posts as $post){
                $managers_array = [];
                foreach($post->owners as $permission){
                    foreach($permission->users as $user){
                        $managers_array[] = $user->id;
                    }
                }
                $post->managers()->sync($managers_array);
                if(count($managers_array) >= 1){
                    $post->poster_id = $managers_array[0];
                }
                $alias_array = [];
                foreach($post->alias as $permission){
                    foreach($permission->users as $user){
                        $alias_array[] = $user->id;
                    }
                }
                if(count($alias_array) >= 1){
                    $post->poster_id = $alias_array[0];
                }
                $programs_array = [];
                $teams_array = [];
                foreach($post->viewability as $permission){
                    foreach($permission->programs as $program){
                        $programs_array[] = $program->id;
                    }
                    foreach($permission->teams as $team){
                        $teams_array[] = $team->id;
                    }
                }
                $post->programs()->sync($programs_array);
                $post->teams()->sync($teams_array);
                $post->save();
            }
            \Flash::success("Team Depth relationships migrated!");
        } catch (\Exception $ex) {
            \Flash::error($ex->getMessage());
        }
    }

    public function onProcessUserDates()
    {
        try {
            $users = User::where('deleted_at','=', NULL)->orderBy('id','ASC')->get();
            foreach($users as $user){
                $user->new_birthday = $user->birth_date;
                $user->new_tenure = $user->commencement_date;
                $user->save();
            }
            \Flash::success("User dates updated!");
        } catch (\Exception $ex) {
            \Flash::error($ex->getMessage());
        }
    }

    public function onProcessAwards()
    {
        try {
            $awards = Award::all();
            $i = 1;
            foreach($awards as $award){
                if($award->hide_nominations_tab == 0){
                    $award->hide_nominations_tab == 1;
                } else {
                    $award->hide_nominations_tab == 0;
                }
                if($award->hide_voting_tab == 0){
                    $award->hide_voting_tab == 1;
                } else {
                    $award->hide_voting_tab == 0;
                }

                $managers_array = [];
                foreach($award->owners as $permission){
                    foreach($permission->users as $user){
                        $managers_array[] = $user->id;
                    }
                }
                $award->managers()->sync($managers_array);

                $managers_array = [];
                foreach($award->nominationsmngr as $permission){
                    foreach($permission->users as $user){
                        $managers_array[] = $user->id;
                    }
                }
                $award->nominationsmanagers()->sync($managers_array);

                $managers_array = [];
                foreach($award->winnernominationsmngr as $permission){
                    foreach($permission->users as $user){
                        $managers_array[] = $user->id;
                    }
                }
                $award->winnersmanagers()->sync($managers_array);

                $teams_array = [];
                foreach($award->viewability as $permission){
                    foreach($permission->programs as $program){
                        $award->awardallprogramview = 1;
                    }
                    foreach($permission->teams as $team){
                        $teams_array[] = $team->id;
                    }
                }
                $award->viewingteams()->sync($teams_array);

                $teams_array = [];
                foreach($award->nomination_viewability as $permission){
                    foreach($permission->programs as $program){
                        $award->awardallprogramnominate = 1;
                        $award->awardallprogramnominateable = 1;
                    }
                    foreach($permission->teams as $team){
                        $teams_array[] = $team->id;
                    }
                }
                $award->nominationteams()->sync($teams_array);

                $teams_array = [];
                foreach($award->vote_viewability as $permission){
                    foreach($permission->programs as $program){
                        $award->awardallprogramvote = 1;
                    }
                    foreach($permission->teams as $team){
                        $teams_array[] = $team->id;
                    }
                }
                $award->votingteams()->sync($teams_array);
                $award->save();
            }
            \Flash::success("Award relationships migrated!");
        } catch (\Exception $ex) {
            \Flash::error($ex->getMessage());
        }
    }

    public function onProcessVotes()
    {
        try {
            $votes = Vote::all();
            foreach($votes as $vote){
                if($vote->nomination){
                    $vote->award_id = $vote->nomination->award_id;
                    $vote->save();
                }
            }
            \Flash::success("Vote relationships migrated!");
        } catch (\Exception $ex) {
            \Flash::error($ex->getMessage());
        }
    }

    public function onFixAnthonyImport()
    {
        try {
            $users = User::whereIn('id',[2791,2933,2649,3061,2594,3565,2648,2531,3223,2270,2529,3224,3514,2241,2592,3222,3245,3632,3248,3246,3244,2254,2486,2387,2383,3462,2770,3278,3342,2303,2215,2826,3041,3272,3203,3349,2654,2575,2798])->get();
            foreach($users as $user){
                $user->runMyPoints(true);
            }
            \Flash::success("Points Ledger Run!");
        } catch (\Exception $ex) {
            \Flash::error($ex->getMessage());
        }
    }
}