<?php

namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Result;
use Codengine\Awardbank\models\ResultType;
use Codengine\Awardbank\models\Program;
use Codengine\Awardbank\models\Team;
use Auth;
use Illuminate\Support\Facades\Log;
use Session;


class ResultsDetail extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $partsservice = 1;
    private $partsworks;
    private $serviceworks;
    private $resultstypesfilter = 1;
    private $slot1;
    private $slot2;
    private $slot3;
    private $slot4;
    private $slot5;
    private $slot5jan;
    private $slot5feb;
    private $slot5mar;
    private $slot5apr;
    private $slot5may;
    private $slot5jun;
    private $slot5jul;
    private $slot5aug;
    private $slot5sep;
    private $slot5oct;
    private $slot5nov;
    private $groupstring;
    private $leaderboardtextservice;
    private $leaderboardtextpart;
    private $currentteamid;
    private $currentteam;
    private $controlfilters;
    private $monthstring;
    private $partdate;
    private $servicedate;

    //title for sprint
    private $partHeader;

    private $leaderboardfirst = 0;
    private $manager = false;
    public $slot1html;
    public $slot2html;
    public $slot3html;
    public $slot4html;
    public $slot5html;
    public $controlfiltershtml;
    public $dashboardview;

    // Edit the title in the sprint

    public function componentDetails()
    {
        return [
            'name' => 'Toyota Specific Result Details Page',
            'description' => 'Toyota Specific Result Details Page',
        ];
    }

    public function defineProperties()
    {
        return [
            'dashboardView' => [
                'title' => 'Show Heading',
                'type' => 'checkbox',
                'default' => false,
            ],
            'monthstring' => [
                'title' => 'Month Text',
                'type' => 'string',
            ],


            //show sprint module
            'showSprintModule' => [
                'title' => 'Show Sprint Module',
                'type' => 'checkbox',
                'default' => true,
            ],

            // First row
            'partHeader' => [
                'title' => 'Part header',
                'type' => 'string',
                'default' => 'Elite Point Opportunity Q3'
            ],
            'partTitleFirst' => [
                'title' => 'Part Title First Row',
                'type' => 'string',
                'default' => 'January 2021 Radiators'
            ],
            'partTitleSecond' => [
                'title' => 'Part Title Second Row',
                'type' => 'string',
                'default' => 'Plan Achieved'
            ],
            'partTitleThird' => [
                'title' => 'Part Title Third Row',
                'type' => 'string',
                'default' => 'January 2021 Points Earned'
            ],
            'partTitleFourth' => [
                'title' => 'Part Title Fourth Row',
                'type' => 'string',
                'default' => 'Fourth'
            ],
            'partTitleFifth' => [
                'title' => 'Part Title Fifth Row',
                'type' => 'string',
                'default' => 'Data'
            ],
            'partDate' => [
                'title' => 'Part Date',
                'type' => 'string',
                'default' => 'December 2021'
            ],

            // Second row
            'serviceHeader' => [
                'title' => 'Service header',
                'type' => 'string',
                'default' => 'Elite Point Opportunity Q3'
            ],
            'serviceTitleFirst' => [
                'title' => 'Service Title First Row',
                'type' => 'string',
                'default' => 'July TSA Volume Plan'
            ],
            'serviceTitleSecond' => [
                'title' => 'Service Title Second Row',
                'type' => 'string',
                'default' => 'July TSA Volume Achieved'
            ],
            'serviceTitleThird' => [
                'title' => 'Service Title Third Row',
                'type' => 'string',
                'default' => 'Data'
            ],
            'serviceTitleFourth' => [
                'title' => 'Service Title Fourth Row',
                'type' => 'string',
                'default' => 'Data'
            ],
            'serviceTitleFifth' => [
                'title' => 'Service Title Fifth Row',
                'type' => 'string',
                'default' => 'Data'
            ],
            'serviceDate' => [
                'title' => 'Service Date',
                'type' => 'string',
                'default' => 'December 2021'
            ],

        ];
    }


    public function init()
    {
        $this->user = Auth::getUser();
        $this->dashboardview = $this->property('dashboardView');
        $this->monthstring =  $this->property('monthstring');
        $this->servicedate = $this->property('serviceDate');
        $this->partdate = $this->property('partDate');
    }

    public function onRun()
    {
        $this->leaderboardfirst = $this->param('leaderboard');
        $this->setSessionDetails();
        $this->checkDealerPartsServiceValid();
        $this->setControlsHtml();
        $this->setHtml();
    }

    public function setHtml()
    {
        $this->setLeaderboardText();
        if ($this->resultstypesfilter == 2 and $this->manager == true) {
            $this->setLeadersHtml();
        } else {
            $this->setTablesHtml();
        }
    }

    /**
        Set Current TeamID and Current Team Name
     **/

    public function setSessionDetails()
    {

        if (null !== Session::get('resultsteamsfilter_' . $this->user->id)) {
            $this->currentteamid = Session::get('resultsteamsfilter_' . $this->user->id);
            $team = Team::find($this->currentteamid);
            if (in_array($this->user->currentTeam->slug, ['ero-2', 'cro-2', 'nro-2', 'sro-2', 'wa-5', 'national', 'national-2021'])) {
                $this->manager = true;
            }
        } else {
            if (strpos($this->user->currentTeam->slug, 'national') !== false) {
                foreach ($this->user->currentTeam->getChildren() as $region) {
                    foreach ($region->getChildren() as $regionteam) {
                        $this->currentteamid = $regionteam->id;
                    }
                }
                $this->manager = true;
            } elseif (in_array($this->user->currentTeam->slug, ['ero-2', 'cro-2', 'nro-2', 'sro-2', 'wa-5'])) {
                foreach ($this->user->currentTeam->getChildren() as $regionteam) {
                    $teams['teams'][$regionteam->id] = $regionteam->name;
                }
                $this->manager = true;
            } else {
                $this->currentteamid = $this->user->current_team_id;
            }
        }
        $this->currentteam = Team::find($this->currentteamid);
        if ($this->currentteam == null) {
            $this->currentteam = Team::find(1782);
        }
        $tags = $this->user->targetingtags->pluck('id')->toArray();
        if (null !== Session::get('resultstagsfilter_' . $this->user->id) and (count($tags) >= 2)) {
            $this->partsservice = Session::get('resultstagsfilter_' . $this->user->id);
        } else {
            $this->partsservice = reset($tags);
        }
        if (null !== Session::get('resultstypesfilter_' . $this->user->id)) {
            $this->resultstypesfilter = Session::get('resultstypesfilter_' . $this->user->id);
        }


        if ($this->dashboardview != 1 and $this->leaderboardfirst == 'leaderboard' and null == Session::get('resultstypesfilter_' . $this->user->id)) {
            $this->resultstypesfilter = 2;
        }
    }

    public function checkDealerPartsServiceValid()
    {
        $this->partsworks = $this->checkTheDealerRightsExists(34, $this->currentteamid);
        $this->serviceworks = $this->checkTheDealerRightsExists(49, $this->currentteamid);

        if ($this->partsworks == false && ($this->partsservice == 1 || $this->partsservice == '1')) {
            $this->partsservice = 2;
        }
        if ($this->serviceworks == false && ($this->partsservice == 2 || $this->partsservice == '2')) {
            $this->partsservice = 1;
        }
    }

    public function checkTheDealerRightsExists($typeid, $teamid)
    {
        $results = Result::where('resulttype_id', '=', $typeid)
            ->where('is_current', '=', 1)
            ->where('team_id', '=', $teamid)
            ->first();
        if ($results) {
            return true;
        } else {
            return false;
        }
    }

    /**
        Return An Array Of Results Based On Parent Groupid & Team
        $teamid = integer id variable of Team Model
        $groupid = integer id variable of Team Model
     **/

    public function setLeadersHtml()
    {
        if ($this->partsservice == 1) {
            $this->groupstring = $this->pluckLeaderboardGroup(34, $this->currentteamid);
            if ($this->dashboardview != 1) {
                $this->setLeadersData('slot1', 34, 33, $this->groupstring);
                $name1 = 'Overall Leaderboard';
                $column1 = 'Ranking Score';
                $this->setLeadersData('slot2', 32, 38, $this->groupstring);
                $name2 = 'Criteria 2: YTD Genuine P&A Wholesale % Growth vs Prev. Year';
                $column2 = 'YTD P&A Growth';
                $this->setLeadersData('slot3', 26, 35, $this->groupstring);
                $name3 = 'Criteria 1: YTD Genuine Parts W/S Result';
                $column3 = 'YTD Parts W/S Volume';
                $this->setLeadersData('slot4', 28, 36, $this->groupstring);
                $name4 = 'Criteria 3: YTD Accessory per New Vehicle Ratio';
                $column4 = 'YTD PNVR';
            } else {
                $this->setLeadersData('slot1', 34, 33, $this->groupstring);
                $name1 = 'Overall Leaderboard';
                $column1 = 'Ranking Score';
                $this->setLeadersData('slot3', 26, 35, $this->groupstring);
                $name3 = 'Criteria 1: YTD Genuine Parts W/S Result';
                $column3 = 'YTD Parts W/S Volume';
                $this->setLeadersData('slot4', 32, 38, $this->groupstring);
                $name4 = 'Criteria 2: YTD Genuine P&A Wholesale % Growth vs Prev. Year';
                $column4 = 'YTD P&A Growth';
                $this->setLeadersData('slot2', 28, 36, $this->groupstring);
                $name2 = 'Criteria 3: YTD Accessory per New Vehicle Ratio';
                $column2 = 'YTD PNVR';
            }
        } else {
            $this->groupstring = $this->pluckLeaderboardGroup(49, $this->currentteamid);
            if ($this->dashboardview != 1) {
                $this->setLeadersData('slot1', 49, 48, $this->groupstring);
                $name1 = 'Overall Leaderboard';
                $column1 = 'Ranking Score';
                $this->setLeadersData('slot2', 45, 52, $this->groupstring);
                $name2 = 'Criteria 2 : YTD TSA Volume';
                $column2 = 'TSA Retention %';
                $this->setLeadersData('slot3', 46, 53, $this->groupstring);
                $name3 = 'Criteria 1 : YTD Service Market Share';
                $column3 = 'FIR Score';
                $this->setLeadersData('slot4', 47, 50, $this->groupstring);
                $name4 = 'Criteria 3: YTD Non/Post TSA Volume';
                $column4 = 'TUS Volume';
            } else {
                $this->setLeadersData('slot1', 49, 48, $this->groupstring);
                $name1 = 'Overall Leaderboard';
                $column1 = 'Ranking Score';
                $this->setLeadersData('slot3', 46, 53, $this->groupstring);
                $name3 = 'Criteria 1 : YTD Service Market Share';
                $column3 = 'FIR Score';
                $this->setLeadersData('slot4', 45, 52, $this->groupstring);
                $name4 = 'Criteria 2 : YTD TSA Volume';
                $column4 = 'TSA Retention %';
                $this->setLeadersData('slot2', 47, 50, $this->groupstring);
                $name2 = 'Criteria 3: YTD Non/Post TSA Volume';
                $column2 = 'TUS Volume';
            }
        }

        $this->buildHtml('slot1html', '@leaderboard', $this->partialLeaderFactory($this->slot1, $name1, $column1));
        $this->buildHtml('slot2html', '@leaderboard', $this->partialLeaderFactory($this->slot2, $name2, $column2));
        $this->buildHtml('slot3html', '@leaderboard', $this->partialLeaderFactory($this->slot3, $name3, $column3));
        $this->buildHtml('slot4html', '@leaderboard', $this->partialLeaderFactory($this->slot4, $name4, $column4));
    }

    public function setLeaderboardText()
    {
        $resultTypeLeaderBoardService = ResultType::where('type', 'leaderboardtextservice')->first();
        $resultTypeLeaderBoardText = ResultType::where('type', 'leaderboardtextpart')->first();

        if ($resultTypeLeaderBoardService && isset($resultTypeLeaderBoardService->id)) {
            $this->leaderboardtextservice = $this->pluckLeaderboardText(
                $resultTypeLeaderBoardService->id, $this->currentteamid
            );
        }

        if ($resultTypeLeaderBoardText && isset($resultTypeLeaderBoardText->id)) {
            $this->leaderboardtextpart = $this->pluckLeaderboardText(
                $resultTypeLeaderBoardText->id,
                $this->currentteamid
            );
        }

    }

    /**
        Return An Array Of Results Based On Parent Groupid & Team
        $teamid = integer id variable of Team Model
        $groupid = integer id variable of Team Model
     **/

    public function setTablesHtml()
    {
        if ($this->partsservice == 1) {
            $this->groupstring = $this->pluckLeaderboardGroup(34, $this->currentteamid);
            $this->setTablesData('slot1', [26, 28, 32, 33, 34]);
            $this->setSprintData();
            $this->setTablesData('slot3', [39, 40, 98, 141, 41]);
            $this->setLeadersData('slot4', 34, 33, $this->groupstring);
            $this->setTablesData('slot5jan', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5feb', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5mar', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5apr', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5may', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5jun', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5jul', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5aug', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5sep', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5oct', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
            $this->setTablesData('slot5nov', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]);
        } else {
            $this->groupstring = $this->pluckLeaderboardGroup(49, $this->currentteamid);
            $this->setTablesData('slot1', [45, 46, 47, 48, 49]);
            $this->setSprintData();
            $this->setTablesData('slot3', [54, 55, 56, 57, 58, 142]);
            $this->setLeadersData('slot4', 49, 48, $this->groupstring);
            $this->setTablesData('slot5jan', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5feb', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5mar', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5apr', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5may', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5jun', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5jul', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5aug', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5sep', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5oct', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
            $this->setTablesData('slot5nov', [42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53]);
        }
        $this->buildHtml('slot1html', '@criteria', $this->partialCriteriaFactory($this->slot1));
        $this->buildHtml('slot2html', '@sprint', $this->slot2);
        $this->buildHtml('slot3html', '@eligibility', $this->partialDataFactory($this->slot3));
        $this->buildHtml('slot4html', '@leaderboard', $this->partialLeaderFactory($this->slot4, 'Overall Leaderboard', 'Ranking Score'));
        $this->buildHtml('slot5html', '@breakdown', [
            'jan' => $this->partialBreakdownFactory($this->slot5jan),
            'feb' => $this->partialBreakdownFactory($this->slot5feb),
            'mar' => $this->partialBreakdownFactory($this->slot5mar),
            'apr' => $this->partialBreakdownFactory($this->slot5apr),
            'may' => $this->partialBreakdownFactory($this->slot5may),
            'jun' => $this->partialBreakdownFactory($this->slot5jun),
            'jul' => $this->partialBreakdownFactory($this->slot5jul),
            'aug' => $this->partialBreakdownFactory($this->slot5aug),
            'sep' => $this->partialBreakdownFactory($this->slot5sep),
            'oct' => $this->partialBreakdownFactory($this->slot5oct),
            'nov' => $this->partialBreakdownFactory($this->slot5nov),
        ]);
    }

    public function setSprintData()
    {
        $this->slot2['partsservice'] = $this->partsservice;
        if ($this->partsservice == 1) {

            $this->slot2['row'] = Result::where('team_id', '=', $this->currentteamid)
                ->where('is_current', '=', 1)
                ->where('resulttype_id', '=', 59)
                ->with('resulttype')
                ->orderBy('row')
                ->get();
        } else {
            $this->slot2['row'] = Result::where('team_id', '=', $this->currentteamid)
                ->where('is_current', '=', 1)
                ->where('resulttype_id', '=', 60)
                ->with('resulttype')
                ->orderBy('row')
                ->get();
        }

        $this->slot2['showSprintModule'] = $this->property('showSprintModule');
        $this->slot2['partHeader'] = $this->property('partHeader');

        //for service
        $this->slot2['serviceHeader'] = $this->property('serviceHeader');


        // first row of 2d array is depend on the current servvice part
        // second row of 2d array is depend on the row of the result
        $title = [
            '1' => [
                '1' => $this->property('partTitleFirst'),
                '2' => $this->property('partTitleSecond'),
                '3' => $this->property('partTitleThird'),
                '4' => $this->property('partTitleFourth'),
                '5' => $this->property('partTitleFifth'),
            ],
            '2' => [
                '1' => $this->property('serviceTitleFirst'),
                '2' => $this->property('serviceTitleSecond'),
                '3' => $this->property('serviceTitleThird'),
                '4' => $this->property('serviceTitleFourth'),
                '5' => $this->property('serviceTitleFifth'),
            ]
        ];
        $this->slot2['title'] = $title;
    }


    /**
        Set Data and Build HTML Elements For Filter Controls
     **/

    public function setControlsHtml()
    {
        $teams = [];

        $program = Program::find($this->user->currentProgram->id);
        $teamset = $program->teams()->whereHas('users', function ($query) {
            $query->where('id', '=', $this->user->id);
        })->get();
        foreach ($teamset as $team) {
            if ( strpos($team->slug, 'national') !== false) {
                foreach ($team->getChildren() as $region) {
                    foreach ($region->getChildren() as $regionteam) {
                        $teams['teams'][$regionteam->id] = $regionteam->name;
                    }
                }
            } elseif (in_array($team->slug, ['ero-2', 'cro-2', 'nro-2', 'sro-2', 'wa-5'])) {
                foreach ($team->getChildren() as $regionteam) {
                    $teams['teams'][$regionteam->id] = $regionteam->name;
                }
            } else {
                $teams['teams'][$team->id] = $team->name;
            }
        }
        if (isset($teams['teams'])) {
            asort($teams['teams']);
        }
        $tags['tags'] = $this->user->targetingtags->pluck('name', 'id')->toArray();
        if ($this->partsworks == false && array_key_exists(1, $tags['tags'])) {
            unset($tags['tags'][1]);
        }
        if ($this->serviceworks == false && array_key_exists(2, $tags['tags'])) {
            unset($tags['tags'][2]);
        }
        //if($this->dashboardview == false and $this->manager == true){
        $type['types'] = [1 => 'Results', 2 => 'Leaderboards'];
        $data = array_merge($teams, $type);
        $data = array_merge($data, $tags);
        /**
        } else{
            $type['types'] = [1 => 'Results', 2 => 'Leaderboards'];
            $data = array_merge($teams, $type);
            $data = array_merge($teams,$tags);
        }
         **/

        $this->controlfilters['currentag'] = $this->partsservice;
        $this->controlfilters['currentteam'] = $this->currentteamid;
        $this->controlfilters['currentype'] = $this->resultstypesfilter;
        $this->controlfilters['data'] = $data;

        $this->buildHtml('controlfiltershtml', '@controlfilter', $this->controlfilters);
    }

    /**
        Set Data for the Result HTML Elements in setTablesHtml();
        $var1 = Criteria Result Group Id
        $var2 = Eligibilty Result Group Id
        $var3 = Leaderboard Result Type Rank Id
        $var4 = Leaderboard Result Score Id
        $var5 = Leaderboard 'Group' String Variable
        $var6 = Breakdown Result Group Id
     **/

    public function setTablesData($slotname, $array)
    {
        if ($slotname == 'slot5jan') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 1);
        } else if ($slotname == 'slot5feb') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 2);
        } else if ($slotname == 'slot5mar') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 3);
        } else if ($slotname == 'slot5apr') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 4);
        } else if ($slotname == 'slot5may') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 5);
        } else if ($slotname == 'slot5jun') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 6);
        } else if ($slotname == 'slot5jul') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 7);
        } else if ($slotname == 'slot5aug') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 8);
        } else if ($slotname == 'slot5sep') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 9);
        } else if ($slotname == 'slot5oct') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 10);
        } else if ($slotname == 'slot5nov') {
            $this->$slotname = $this->getResultArrayByTeamMonth($this->currentteamid, $array, 11);
        } else {
            $this->$slotname = $this->getResultArrayByTeam($this->currentteamid, $array);
        }
    }

    /**
        Set Data for the Result HTML Elements in setTablesHtml();
        $var1 = Criteria Result Group Id
        $var2 = Eligibilty Result Group Id
        $var3 = Leaderboard Result Type Rank Id
        $var4 = Leaderboard Result Score Id
        $var5 = Leaderboard 'Group' String Variable
        $var6 = Breakdown Result Group Id
     **/

    public function setLeadersData($slotname, $rank, $score, $groupstring)
    {
        $this->$slotname = $this->getLeaderboardArray($rank, $score, $groupstring);
    }



    /**
        Prepare the variables for Partial rendering;
        $models = array of models for results
     **/

    public function partialDataFactory($models)
    {
        $array = [];
        $array['data'] = $models;
        $array['teamname'] = $this->currentteam->name;
        $array['groupstring'] = $this->groupstring;
        $array['dashboardview'] = $this->dashboardview;
        if ($this->partsservice == 2) {
            $array['partsservice'] = 'Service';
        } else {
            $array['partsservice'] = 'Parts';
        }
        return $array;
    }

    /**
        Prepare the variables for Partial rendering;
        $models = array of models for results
        $title = The label for the table
        $column = The lavel for the table last row.
     **/

    public function partialLeaderFactory($models, $title, $column)
    {
        $array = [];
        $array['data'] = $models;
        $array['teamname'] = $this->currentteam->name;
        $array['groupstring'] = $this->groupstring;
        // $array['leaderboardtext'] = $this->leaderboardtext;
        $array['leaderboardtextservice'] = $this->leaderboardtextservice;
        $array['leaderboardtextpart'] = $this->leaderboardtextpart;

        $array['dashboardview'] = $this->dashboardview;
        $array['monthstring'] = $this->monthstring;
        $array['partdate'] = $this->partdate;
        $array['servicedate'] = $this->servicedate;

        $array['partHeader'] = $this->partHeader;
        $array['title'] = $title;
        $array['column'] = $column;
        if ($this->partsservice == 2) {
            $array['partsservice'] = 'Service';
        } else {
            $array['partsservice'] = 'Parts';
        }

        return $array;
    }


    /**
        Prepare the variables for Breakdown Partial rendering;
        $models = array of models for results
     **/

    public function partialBreakdownFactory($models)
    {
        $array = [];
        $array['teamname'] = $this->currentteam->name;
        $array['groupstring'] = $this->groupstring;
        $array['dashboardview'] = $this->dashboardview;
        if ($this->partsservice == 2) {
            $array['partsservice'] = 'Service';
            $array['data'][0] = $models->first(function ($item) {
                return $item->resulttype_id == 53;
            });
            $array['data'][1] = $models->first(function ($item) {
                return $item->resulttype_id == 46;
            });
            $array['data'][3] = $models->first(function ($item) {
                return $item->resulttype_id == 52;
            });
            $array['data'][4] = $models->first(function ($item) {
                return $item->resulttype_id == 45;
            });
            $array['data'][5] = $models->first(function ($item) {
                return $item->resulttype_id == 44;
            });
            $array['data'][6] = $models->first(function ($item) {
                return $item->resulttype_id == 47;
            });
            $array['data'][7] = $models->first(function ($item) {
                return $item->resulttype_id == 48;
            });
        } else {
            $array['partsservice'] = 'Parts';
            $array['data'][0] = $models->first(function ($item) {
                return $item->resulttype_id == 25;
            });
            $array['data'][1] = $models->first(function ($item) {
                return $item->resulttype_id == 26;
            });
            $array['data'][2] = $models->first(function ($item) {
                return $item->resulttype_id == 29;
            });
            $array['data'][3] = $models->first(function ($item) {
                return $item->resulttype_id == 30;
            });
            $array['data'][4] = $models->first(function ($item) {
                return $item->resulttype_id == 31;
            });
            $array['data'][5] = $models->first(function ($item) {
                return $item->resulttype_id == 32;
            });
            $array['data'][6] = $models->first(function ($item) {
                return $item->resulttype_id == 27;
            });
            $array['data'][7] = $models->first(function ($item) {
                return $item->resulttype_id == 28;
            });
            $array['data'][8] = $models->first(function ($item) {
                return $item->resulttype_id == 33;
            });
        }

        return $array;
    }

    /**
        Prepare the variables for Criteria Partial rendering;
        $models = array of models for results
     **/

    public function partialCriteriaFactory($models)
    {
        $array = [];
        $array['teamname'] = $this->currentteam->name;
        $array['groupstring'] = $this->groupstring;
        $array['dashboardview'] = $this->dashboardview;
        if ($this->partsservice == 2) {
            $array['partsservice'] = 'Service';
            $array['data'][1] = $models->first(function ($item) {
                return $item->resulttype_id == 46;
            });
            $array['data'][2] = $models->first(function ($item) {
                return $item->resulttype_id == 45;
            });
            $array['data'][3] = $models->first(function ($item) {
                return $item->resulttype_id == 47;
            });
            $array['data'][4] = $models->first(function ($item) {
                return $item->resulttype_id == 48;
            });
            $array['data'][5] = $models->first(function ($item) {
                return $item->resulttype_id == 49;
            });
        } else {
            $array['partsservice'] = 'Parts';
            $array['data'][1] = $models->first(function ($item) {
                return $item->resulttype_id == 26;
            });
            $array['data'][2] = $models->first(function ($item) {
                return $item->resulttype_id == 32;
            });
            $array['data'][3] = $models->first(function ($item) {
                return $item->resulttype_id == 28;
            });
            $array['data'][4] = $models->first(function ($item) {
                return $item->resulttype_id == 33;
            });
            $array['data'][5] = $models->first(function ($item) {
                return $item->resulttype_id == 34;
            });
        }
        $array['data'] = array_filter($array['data'], 'strlen');
        return $array;
    }

    /**
        Return An Array Of Results Based On Parent Groupid & Team
        $teamid = integer id variable of Team Model
        $resulttypearray = array of ResultTypes to Collect
     **/

    public function getResultArrayByTeam($teamid, $resulttypearray)
    {
        return Result::where('team_id', '=', $teamid)
            ->where('is_current', '=', 1)
            ->whereHas('resulttype', function ($query) use ($resulttypearray) {
                $query->whereIn('id', $resulttypearray);
            })
            ->with('resulttype')
            ->get();
    }

    public function getResultArrayByTeamMonth($teamid, $resulttypearray, $month_index)
    {
        return Result::where('team_id', '=', $teamid)
            ->where('month_index', '=', $month_index)
            ->whereHas('resulttype', function ($query) use ($resulttypearray) {
                $query->whereIn('id', $resulttypearray);
            })
            ->with('resulttype')
            ->get();
    }

    /**
        Return An Array Of Results Based On Parent Groupid & Team
        $teamid = integer id variable of Team Model
        $groupid = integer id variable of Team Model
     **/

    public function pluckLeaderboardGroup($typeid, $teamid)
    {
        $result = Result::where('resulttype_id', '=', $typeid)
            ->where('is_current', '=', 1)
            ->where('team_id', '=', $teamid)
            ->first();
        if ($result) {
            $string = $result->string;
        } else {
            $string = '';
        }
        return $string;
    }

    public function pluckLeaderboardText($typeid, $teamid)
    {
        $result = Result::where('resulttype_id', '=', $typeid)
            ->where('is_current', '=', 1)
            ->where('team_id', '=', $teamid)
            ->first();

        if ($result) {
            $string = $result->string;
        } else {
            $string = '';
        }
        return $string;
    }

    /**
        Return An Array Of Results Based On Parent TypeID
        $typeid = integer id variable of ResultTypeModel
        $attachid = Pass through integer id variable of ResultTypeModel for Score Value
        $typestring = 'Emerald Lakes, Mida Peak,The Remarkables'
     **/

    public function getLeaderboardArray($typeid, $attachid, $typestring)
    {
        $results = Result::where('resulttype_id', '=', $typeid)
            ->where('is_current', '=', 1)
            ->where('string', '=', $typestring)
            ->orderBy('value', 'ASC')
            ->with('team');
        if ($this->dashboardview == true) {
            $results = $results->limit(5);
        }
        $results = $results->get();
        return $this->leaderRowFactory($results, $attachid);
    }

    /**
        Return An Array Of Results Based On Parent TypeID
        $typeid = integer id variable of ResultTypeModel for Score Value
     **/

    public function leaderRowFactory($results, $attachid)
    {
        $return = [];
        foreach ($results as $result) {
            if ($result->team) {
                $score = Result::where('resulttype_id', '=', $attachid)->where('team_id', '=', $result->team->id)->where('is_current', '=', 1)->first();
                $subreturn['rank'] = $result->value;
                $subreturn['team'] = $result->team->name;
                $subreturn['score'] = $score;
            } else {
                $subreturn['rank'] = 'Not Found';
                $subreturn['team'] = 'Not Found';
                $subreturn['score'] = 'Not Found';
            }
            $return[] = $subreturn;
        }
        return $return;
    }

    /**
        Return An Array Of Results Based On Parent TypeID
        $typeid = integer id variable of ResultTypeModel for Score Value
     **/

    public function breakdownBenchmark($results)
    {
        $return = [];
        foreach ($results as $result) {
            $score = Result::where('resulttype_id', '=', $attachid)->where('team_id', '=', $result->team->id)->first();
            $score = $score->value;
            $subreturn['rank'] = $result->value;
            $subreturn['team'] = $result->team->name;
            $subreturn['score'] = $score;
            $return[] = $subreturn;
        }
        return $return;
    }

    /**
        Return HTML code from a partical into Public Variable
        $returnvar = public variable name to return to
        $partialname = string name of partial (include @)
        $values = array of values required for the partial
     **/

    public function buildHtml($returnvar, $partialname, $values)
    {
        $this->$returnvar = $this->renderPartial(
            $partialname,
            [
                'results' => $values,
            ]
        );
    }

    /** AJAX REQUEST **/

    public function onUpdatePage()
    {
        $type = post('data-type');
        $id = post('data-id');
        if ($type == 'tags') {
            Session::put('resultstagsfilter_' . $this->user->id, $id);
        }
        if ($type == 'teams') {
            Session::put('resultsteamsfilter_' . $this->user->id, $id);
        }
        if ($type == 'types') {
            Session::put('resultstypesfilter_' . $this->user->id, $id);
        }
        $this->setSessionDetails();
        $this->checkDealerPartsServiceValid();
        $this->setControlsHtml();
        $this->setHtml();
        $result['slot1html'] = $this->slot1html;
        $result['slot2html'] = $this->slot2html;
        $result['slot3html'] = $this->slot3html;
        $result['slot4html'] = $this->slot4html;
        $result['slot5html'] = $this->slot5html;
        $result['controlfilters'] = $this->controlfiltershtml;
        return $result;
    }
}
