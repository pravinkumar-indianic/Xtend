<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\models\Result;
use Codengine\Awardbank\models\ResultType;
use Codengine\Awardbank\models\ResultGroup;
use Codengine\Awardbank\models\Program;
use Auth;
use Codengine\Awardbank\Models\Team;
use RainLab\User\Models\User;
use Session;
use System\Helpers\DateTime;
use Carbon\Carbon;
use DB;

class ResultsDashboard extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $groups;
    public $results;
    public $specificResults;
    public $currentProgram;
    public $moduleEnabled;
    public $ownerArray;
    public $level;
    public $managedid;
    public $targetidsarray;
    public $regionList;
    public $regiontags;
    public $teamList;
    public $teamtags;
    public $userrole;
    public $userList;
    public $usertags;

    public function componentDetails()
    {
        return [
            'name' => 'Products List Cover Flow',
            'description' => 'Products List Cover Flow',
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

        if($this->user != null){

            $this->currentProgram = Program::where('id','=', $this->user->current_program_id)->first();

            $this->moduleEnabled = $this->currentProgram ? $this->currentProgram->module_allow_results : false;

            if($this->moduleEnabled == true){

                $this->groups = ResultGroup::where('program_id', '=', $this->user->current_program_id)
                    ->where('display_dashboard','=',1);

                if($this->user->currentProgram->use_targeting_tags == true){

                    $targetingtagids = $this->user->targetingtags->pluck('id')->toArray();

                    $this->groups = $this->groups->whereHas('targetingtags', function($query) use ($targetingtagids){
                        $query->whereIn('id', $targetingtagids);
                    });
                }

                $this->groups = $this->groups->get();


                $this->ownerArray = $this->user->owner_array;

                if(isset($this->ownerArray['programs'])){
                    if(array_key_exists($this->currentProgram->id,$this->ownerArray['programs'])){
                        $this->level = 'program';
                        $this->managedid = $this->currentProgram->id;
                    }
                }

                $this->setAllResults();
            }
        }
    }

    public function onRun()
    {
        $this->setUserRole();
        if ($this->moduleEnabled == true) {
            if ($this->level != null && $this->managedid != null) {
                $this->setSpecificResults($this->level, $this->managedid);
            } else {
                $this->setSpecificResults('user', $this->user->id);
            }
        }
    }

    public function setUserRole($view = false) {
        //If a specific view is required the role needs to be overridden
        if ($view) {
            switch ($view) {
                case 'user':
                    $this->userrole = 'user';
                    return;
                case 'team':
                    $this->userrole = 'team';
                    return;
                case 'region':
                    $this->userrole = 'regional';
                    return;
            }
        }

        $this->userrole = 'user';
        $regionalteams = ['NSW / ACT', 'QLD', 'SA / NT', 'VIC / TAS', 'WA'];

        if (!empty($this->user->owner_array['programs'])) {
            $this->userrole = 'national';
        } else {
            if (!empty($this->user->owner_array['teams'])) {
                $this->userrole = 'team';

                foreach ($this->user->owner_array['teams'] as $team) {
                    if (in_array($team, $regionalteams)) {
                        $this->userrole = 'regional';
                    }
                }
            }
        }
    }

    public function setAllResults(){

        $tables = [];

        $regiontags = [];
        $teamtags = [];
        $usertags = [];

        $resultRows = $this->prefetchResults();
        $results = $this->groupResultsByResultType($resultRows);
        $this->regionList = $this->prefetchResultRegions();
        $this->teamList = $this->prefetchResultTeams($resultRows);
        $this->userList = $this->prefetchResultUsers($resultRows);

        foreach($this->groups as $group){

            $tables[$group->id]['meta']['name'] = $group->name;
            $tables[$group->id]['meta']['type'] = $group->type;
            $tables[$group->id]['meta']['label'] = $group->label;
            $tables[$group->id]['meta']['description'] = $group->description;

            foreach($group->resulttypes as $type){

                $tables[$group->id][$type->id]['meta']['id'] = $type->id;
                $tables[$group->id][$type->id]['meta']['type'] = $type->type;
                $tables[$group->id][$type->id]['meta']['label'] = $type->label;
                $tables[$group->id][$type->id]['meta']['benchmark'] = $type->benchmark;

                //Fetch all results within the list of result types

                //Display comments based on the attachment level
                if ($type->type == 'comment') {
                    if (!empty($results[$type->id])) {
                        foreach ($results[$type->id] as $result) {
                            $tables[$group->id][$type->id]['comments'][] = $result;
                        }
                    }
                } else {
                    if (!empty($results[$type->id])) {
                        foreach ($results[$type->id] as $result) {
                            $regionpassed = false;
                            $teampassed = false;

                            if (isset($tables[$group->id][$type->id]['program'][$this->currentProgram->id]['benchmark'])) {
                                $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['benchmark'] = $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['benchmark'] + $type->benchmark;
                            } else {
                                $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['benchmark'] = 0 + $type->benchmark;
                            }
                            if (isset($tables[$group->id][$type->id]['program'][$this->currentProgram->id]['value'])) {
                                $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['value'] = $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['value'] + $result->value;
                            } else {
                                $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['value'] = 0 + $result->value;
                            }
                            //$group->name should be the same as $this->currentProgram->name
                            $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['name'] = $this->currentProgram->name;
                            $tables[$group->id][$type->id]['program'][$this->currentProgram->id]['string'] = $result->string;

                            $resultRegion = empty($result->region_id) ? null : (empty($this->regionList[$result->region_id]) ? null : $this->regionList[$result->region_id]);
                            if ($resultRegion) {
                                if ($this->level == 'program') {
                                    $regiontags[$resultRegion->id] = $resultRegion->name;
                                } elseif (isset($this->ownerArray['regions'])) {
                                    if (array_key_exists($resultRegion->id, $this->ownerArray['regions'])) {
                                        $regiontags[$resultRegion->id] = $resultRegion->name;
                                        $regionpassed = true;
                                        $this->level = 'region';
                                        $this->managedid = $resultRegion->id;
                                    }
                                }
                                if (isset($tables[$group->id][$type->id]['region'][$resultRegion->id]['benchmark'])) {
                                    $tables[$group->id][$type->id]['region'][$resultRegion->id]['benchmark'] = $tables[$group->id][$type->id]['region'][$resultRegion->id]['benchmark'] + $type->benchmark;
                                } else {
                                    $tables[$group->id][$type->id]['region'][$resultRegion->id]['benchmark'] = 0 + $type->benchmark;
                                }
                                if (isset($tables[$group->id][$type->id]['region'][$resultRegion->id]['value'])) {
                                    $tables[$group->id][$type->id]['region'][$resultRegion->id]['value'] = $tables[$group->id][$type->id]['region'][$resultRegion->id]['value'] + $result->value;
                                } else {
                                    $tables[$group->id][$type->id]['region'][$resultRegion->id]['value'] = 0 + $result->value;
                                }
                                $tables[$group->id][$type->id]['region'][$resultRegion->id]['name'] = (string)$resultRegion->name;
                                $tables[$group->id][$type->id]['region'][$resultRegion->id]['string'] = $result->string;
                            }

                            $resultTeam = empty($result->team_id) ? null : (empty($this->teamList[$result->team_id]) ? null : $this->teamList[$result->team_id]);
                            if ($resultTeam) {
                                if ($this->level == 'program' || $regionpassed == true) {
                                    $teamtags[$resultTeam->id] = $resultTeam->name;
                                } elseif (isset($this->ownerArray['teams'])) {
                                    if (array_key_exists($resultTeam->id, $this->ownerArray['teams'])) {
                                        $teamtags[$resultTeam->id] = $resultTeam->name;
                                        $teampassed = true;
                                        if ($this->level == null && $this->managedid == null) {
                                            $this->level = 'team';
                                            $this->managedid = $resultTeam->id;
                                        }
                                    }
                                }

                                if (isset($tables[$group->id][$type->id]['team'][$resultTeam->id]['benchmark'])) {
                                    $tables[$group->id][$type->id]['team'][$resultTeam->id]['benchmark'] = $tables[$group->id][$type->id]['team'][$resultTeam->id]['benchmark'] + $type->benchmark;
                                } else {
                                    $tables[$group->id][$type->id]['team'][$resultTeam->id]['benchmark'] = 0 + $type->benchmark;
                                }

                                if (isset($tables[$group->id][$type->id]['team'][$resultTeam->id]['value'])) {
                                    $tables[$group->id][$type->id]['team'][$resultTeam->id]['value'] = $tables[$group->id][$type->id]['team'][$resultTeam->id]['value'] + $result->value;
                                } else {
                                    $tables[$group->id][$type->id]['team'][$resultTeam->id]['value'] = 0 + $result->value;
                                }

                                $tables[$group->id][$type->id]['team'][$resultTeam->id]['name'] = (string)$resultTeam->name;
                                $tables[$group->id][$type->id]['team'][$resultTeam->id]['string'] = $result->string;
                            }

                            $resultUser = empty($result->user_id) ? null : (empty($this->userList[$result->user_id]) ? null : $this->userList[$result->user_id]);
                            if ($resultUser) {
                                if ($teampassed == true || $regionpassed == true || $this->level == 'program') {
                                    if ($this->currentProgram->use_business_names == true) {
                                        $usertags[$resultUser->id] = $resultUser->business_name;
                                    } else {
                                        $usertags[$resultUser->id] = $resultUser->full_name;
                                    }
                                }
                                if (isset($tables[$type->id][$group->id]['user'][$resultUser->id]['benchmark'])) {
                                    $tables[$group->id][$type->id]['user'][$resultUser->id]['benchmark'] = $tables[$group->id][$type->id]['user'][$resultUser->id]['benchmark'] + $type->benchmark;
                                } else {
                                    $tables[$group->id][$type->id]['user'][$resultUser->id]['benchmark'] = 0 + $type->benchmark;
                                }
                                if (isset($tables[$type->id][$group->id]['user'][$resultUser->id]['value'])) {
                                    $tables[$group->id][$type->id]['user'][$resultUser->id]['value'] = $tables[$group->id][$type->id]['user'][$resultUser->id]['value'] + $result->value;
                                } else {
                                    $tables[$group->id][$type->id]['user'][$resultUser->id]['value'] = 0 + $result->value;
                                }
                                $tables[$group->id][$type->id]['user'][$resultUser->id]['string'] = $result->string;
                                if ($this->currentProgram->use_business_names == true) {
                                    $tables[$group->id][$type->id]['user'][$resultUser->id]['name'] = (string)$resultUser->business_name;
                                } else {
                                    $tables[$group->id][$type->id]['user'][$resultUser->id]['name'] = (string)$resultUser->full_name;
                                }
                            }
                        }
                    }
                }
            }
        }

        asort($regiontags);
        asort($teamtags);
        asort($usertags);
        $this->regiontags = $regiontags;
        $this->teamtags = $teamtags;
        $this->usertags = $usertags;
        $this->results = $tables;
    }

    private function prefetchResults(){
        //Pre-fetch all results for all available result types
        $types = [];
        if (!empty($this->groups)) {
            foreach ($this->groups as $group) {
                foreach ($group->resulttypes as $type) {
                    $types[] = $type->id;
                }
            }
        }

        $results = [];
        if (!empty($types)) {
            $results = Result::where('is_current','=', 1)->whereIn('resulttype_id', $types)->get();
        }

        return $results;
    }

    private function groupResultsByResultType($rows){
        $results = [];
        foreach ($rows as $row) {
            $results[$row->resulttype_id][] = $row;
        }

        return $results;
    }

    private function prefetchResultRegions(){
        $regions = [];
        $data = Region::where('program_id', '=', $this->currentProgram->id)->get();
        if (!empty($data)) {
            foreach ($data as $row) {
                $regions[$row->id] = $row;
            }
        }

        return $regions;
    }

    private function prefetchResultTeams($rows){
        $teams = [];
        $teamIds = [];

        foreach ($rows as $result) {
            if (!in_array($result->team_id, $teamIds)) {
               $teamIds[] = $result->team_id;
            }
        }

        if (!empty($teamIds)) {
            $data = Team::whereIn('id', $teamIds)->get();
            if (!empty($data)) {
                foreach ($data as $row) {
                    $teams[$row->id] = $row;
                }
            }
        }

        return $teams;
    }

    private function prefetchResultUsers($rows){
        $users = [];
        $userIds = [];

        foreach ($rows as $result) {
            if (!in_array($result->user_id, $userIds)) {
                $userIds[] = $result->user_id;
            }
        }

        if (!empty($userIds)) {
            $data = User::whereIn('id', $userIds)->get();
            if (!empty($data)) {
                foreach ($data as $row) {
                    $users[$row->id] = $row;
                }
            }
        }

        return $users;
    }

    public function setSpecificResults($type,$id){
        $specificResults = [];

        if (!empty($this->results)) {
            foreach ($this->results as $key => $value) {

                if (isset($value['meta']['type']) && $value['meta']['type'] == 'comments') {
                    $specificResults['comments'] = ['meta' => $value['meta']];
                    foreach ($value as $key2 => $value2) {
                        if ($key2 != 'meta' && !empty($value2['comments'])) {
                            $specificResults['comments'][$value2['meta']['label']] = [];
                            foreach ($value2['comments'] as $comment) {
                                $displayComment = $this->getCommentVisibility($comment, $type, $id);
                                if ($displayComment) {
                                    $specificResults['comments'][$value2['meta']['label']][] = $comment->string;
                                }
                            }
                        }
                    }
                } else {
                    foreach ($value as $key2 => $value2) {
                        if ($key2 == 'meta') {
                            $specificResults[$key]['meta']['name'] = $value2['name'];
                            $specificResults[$key]['meta']['type'] = $value2['type'];
                            $specificResults[$key]['meta']['label'] = $value2['label'];
                            $specificResults[$key]['meta']['description'] = $value2['description'];

                        } elseif ($key2 == 'leaderboard') {
                            foreach ($value2 as $key3 => $value3) {
                                if ($value3['rank'] <= 5) {
                                    $specificResults[$key][$key2][$value3['rank']]['rank'] = $value3['rank'];
                                    $specificResults[$key][$key2][$value3['rank']]['dealer'] = $value3['dealer'];
                                    $specificResults[$key][$key2][$value3['rank']]['score'] = $value3['score'];
                                }
                            }
                        } else {
                            $specificResults[$key][$key2]['meta'] = $value2['meta'];
                            if (isset($value2[$type][$id])) {
                                $specificResults[$key]['meta']['targetname'] = $value2[$type][$id]['name'];
                                $specificResults[$key][$key2]['result'] = $value2[$type][$id];
                            } else {
                                $specificResults[$key][$key2]['result']['name'] = 'Not Found';
                                $specificResults[$key][$key2]['result']['value'] = 100000000000000;
                                $specificResults[$key][$key2]['result']['string'] = 'Not Found';
                            }

                        }
                    }
                }
            }
        }

        $this->specificResults = $specificResults;
    }

    public function getCommentVisibility($comment, $type , $id) {
        $hasaccess = false;
        switch ($comment->attachment_level) {
            case 'user':
                {
                    if (!empty($comment->user_id) && !empty($this->userList[$comment->user_id])) {
                        $user = $this->userList[$comment->user_id];
                        if ($user->id == $this->user->id || $type == 'user' && $user->id == $id) {
                            $hasaccess = true;
                        }
                    }
                }
                break;
            case 'team':
                {
                    $user = in_array($type, ['user']) ? $user = User::find($id) : $this->user;
                    if ($user && (isset($user->current_all_teams_id) && in_array(
                                $comment->team->id,
                                $user->current_all_teams_id
                            ))
                    ) {
                        $hasaccess = true;
                    }
                }
                break;
            case 'region':
                $hasaccess = false;
                break;
            default:
                $hasaccess = true;
        }

        return $hasaccess;
    }

    public function onUpdateResults(){

        $type = post('data-type');
        $id = post('data-id');
        $this->setSpecificResults($type, $id);

        if ($this->currentProgram->id == 104) {
            $this->setUserRole($type);
            $result['html'] = $this->renderPartial('@tables_render_drive_program',
                [
                    'results' => $this->specificResults,
                    'userrole' => $type == 'user' ? 'user' : $this->userrole
                ]
            );
        } else {
            $result['html'] = $this->renderPartial('@tables_render',
                [
                    'results' => $this->specificResults,
                ]
            );
        }

        return $result;

    }

}
