<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Auth;
use Codengine\Awardbank\Models\LeaderboardResult;
use Codengine\Awardbank\Models\ResultsWidgetSettings;
use Codengine\Awardbank\Models\ScorecardCriteria;
use Codengine\Awardbank\Models\ScorecardResult;
use Codengine\Awardbank\Models\ScorecardSection;
use Illuminate\Support\Facades\DB;
use RainLab\User\Models\User;

/**
 * Class Scorecard
 * @package Codengine\Awardbank\Components
 */

class Leaderboard extends ComponentBase
{
    private $user;
    private $program;
    private $crm;
    private $month;
    private $settings;
    private $section = 'leaderboard';

    public function componentDetails()
    {
        return [
            'name' => 'Leaderboard',
            'description' => 'Leaderboard'
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
        if ($this->user && $this->user->currentProgram) {
            $this->program = $this->user->currentProgram;
            $this->getSettings();
            $this->getMonth();
        }
    }

    public function onRun()
    {
        $this->crm = User::where('crm', '=', $this->param('crm_id'))->first();
    }

    public function onRender()
    {
        $this->coreLoadSequence();
    }

    public function coreLoadSequence()
    {
        $this->generateHtml();
    }

    public function generateHtml()
    {
        $this->page['display_points'] = $this->settings['leaderboard_display_points'] ?? 'off';
        $this->page['data'] = $this->getData($this->program);
        $this->page['user'] = $this->user;
        $this->page['month'] = $this->month;
        $this->page['section'] = $this->section;
    }

    private function getSettings() {
        $settings = ResultsWidgetSettings::where('program_id', '=', $this->program->id)
            ->whereIn('key', ['leaderboard_display_year', 'leaderboard_display_month', 'leaderboard_display_points'])
            ->pluck('value', 'key')
            ->toArray();

        $this->settings = $settings;
    }

    private function getMonth() {
        $year = $this->settings['leaderboard_display_year'] ?? date('Y');
        if (!empty($this->settings['leaderboard_display_month'])) {
            $month = \DateTime::createFromFormat('m', $this->settings['leaderboard_display_month']);
            $this->month = $month->format($year.'-m-01');
        } else {
            $currentMonth = intval(date('m'));
            if ($currentMonth < 3) {
                $this->month = date($year.'-03-01');
            } elseif ($currentMonth > 8) {
                $this->month = date($year.'-08-01');
            } else {
                $this->month = date($year.'-m-01');
            }
        }
    }

    private function getData() {
        $programSections = ScorecardSection::where('program_id', '=', $this->program->id)->pluck('id');
        if ($programSections) {
            $programCriteria = ScorecardCriteria::whereIn('scorecard_section_id', $programSections)->pluck('id');
            if ($programCriteria) {
                switch ($this->section) {
                    case 'leaderboard' : return $this->getLeaderboardData($programCriteria);
                    case 'newcomers' : return $this->getSectionData('points');
                    case 'improved' :
                    case 'engaged' : return $this->getSectionData('id');
                }
            }
        }

        return [];
    }

    private function getLeaderboardData($programCriteria)
    {
        $data = ScorecardResult::selectRaw(
            'codengine_awardbank_scorecard_results.crm,
                    users.business_name,
                    users.state,
                    sum(codengine_awardbank_scorecard_results.points) as score'
        )
            ->where('month', '>=', date('Y-03-01'))
            ->where('month', '<=', $this->month)
            ->whereIn('scorecard_criteria_id', $programCriteria)
            ->join(DB::raw('(SELECT * FROM users GROUP BY crm) AS users'),
                'users.crm' ,'=', 'codengine_awardbank_scorecard_results.crm')
            ->groupBy('users.crm', 'users.id')
            ->orderBy('score', 'DESC')
            ->limit(500)
            ->get()
            ->toArray();

        return $data;
    }

    private function getSectionData($orderBy = null)
    {
        $query = LeaderboardResult::where('section', '=', $this->section)
            ->where('month', '=', $this->month)
            ->where('program_id', '=', $this->program->id)
            ->selectRaw('codengine_awardbank_results_leaderboard.*,
                SUM(codengine_awardbank_results_leaderboard.points) as total_points')
            ->groupBy('codengine_awardbank_results_leaderboard.crm');

        if (!empty($orderBy)) {
            if ($orderBy == 'points') {
                $query->orderBy('points', 'DESC');
            } elseif ($orderBy == 'id') {
                $query->orderBy('id', 'ASC');
            }
        }

        $data = $query->get()
            ->toArray();

        $userData = User::select('crm', 'state', 'business_name')
            ->whereNotNull('crm')
            ->whereRaw('LENGTH(crm)>0')
            ->get()
            ->toArray();

        $users = [];
        foreach ($userData as $user) {
            $users[$user['crm']] = $user;
        }

        foreach ($data as $key => $record) {
            if (isset($users[$record['crm']])) {
                $data[$key]['state'] = $users[$record['crm']]['state'];
                $data[$key]['business_name'] = $users[$record['crm']]['business_name'];
            } else {
                $data[$key]['state'] = '';
                $data[$key]['business_name'] = '';
            }
        }

        return $data;
    }

    public function onSectionChange()
    {
        if (!empty(post('id'))) {
            $this->section = post('id');
            $view = $this->section == 'leaderboard' ? 'leaderboard' : 'tables';

            $result['html']['#navigation'] = $this->renderPartial('@navigation', [
                'section' => $this->section
            ]);

            $result['html']['#leaderboard-tables'] = $this->renderPartial('@'.$view, [
                'data' => $this->getData(),
                'user' => $this->user,
                'month' => $this->getMonth(),
                'section' => $this->section,
                'display_points' => $this->settings['leaderboard_display_points'] ?? 'off'
            ]);

            return $result;
        }
    }
}
