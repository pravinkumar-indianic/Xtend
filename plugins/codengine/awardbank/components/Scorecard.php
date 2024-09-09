<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Auth;
use Codengine\Awardbank\Models\ScorecardCriteria;
use Codengine\Awardbank\Models\ScorecardResult;
use Codengine\Awardbank\Models\ScorecardSection;
use Codengine\Awardbank\Models\SocialPost;
use Codengine\Awardbank\Models\SocialPostResponse;
use Event;
use Config;
use Illuminate\Support\Facades\Request;
use October\Rain\Exception\AjaxException;
use RainLab\User\Models\User;
use Response;

/**
 * Class Scorecard
 * @package Codengine\Awardbank\Components
 */

class Scorecard extends ComponentBase
{
    private $user;
    private $program;
    private $pharmacy;
    private $step = 1;

    public function componentDetails()
    {
        return [
            'name'        => 'Scorecard',
            'description' => 'Scorecard'
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
        }

        if (!empty(input('step')) && intval(input('step') > 0 )) {
            $this->step = intval(input('step'));
        }
    }

    public function onRun()
    {
        $this->pharmacy = User::where('crm', '=', $this->param('crm_id'))->first();
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
        $this->page['data'] = empty($this->pharmacy->crm) ? [] : $this->getData($this->pharmacy->crm);
        $this->page['user'] = $this->user;
        $this->page['pharmacy'] = $this->pharmacy;
        $this->page['step'] = $this->step;
    }

    private function getData($pharmacy)
    {
        $data = [];
        $totalPoints = 0;

        $allStepCriteria = $this->getAllStepCriteria();
        if (!empty($allStepCriteria)) {
            $totalPoints = ScorecardResult::where('crm', '=', $pharmacy)
                ->whereIn('scorecard_criteria_id', $allStepCriteria)
                ->where('month', '>=', date('Y-03-01'))
                ->where('month', '<=', date('Y-08-01'))
                ->get()
                ->sum('points');
        }

        $data['total_points'] = $totalPoints;

        $sections = $this->getStepSections();
        $scorecardResultCriteria = $this->getStepCriteria();
        if ($scorecardResultCriteria) {
            //It's always May -> Oct
            $scorecardResults = ScorecardResult::where('crm', '=', $pharmacy)
                ->whereIn('scorecard_criteria_id', $scorecardResultCriteria)
                ->where('month', '>=', date('Y-03-01'))
                ->where('month', '<=', date('Y-08-01'))
                ->get();

            //$sections = [];
            $criteria = [];
            $months = [];

            //Pre-populate the array Mar - Aug
            for ($index = 3; $index <= 8; $index++) {
                $months[] = date('Y'). '-' . (str_pad($index, 2, '0', STR_PAD_LEFT)) . '-01';
            }

            foreach ($scorecardResults as $result) {
                $criteria[$result->scorecard_criteria->id] = $result->scorecard_criteria;
            }

            //Inject missing criteria as they want to display all of them even if there's no results available
            $missingCriteria = $this->getStepMissingCriteria(array_keys($criteria));
            if (!empty($missingCriteria)) {
                foreach ($missingCriteria as $missingCriterium) {
                    $criteria[$missingCriterium->id] = $missingCriterium;
                }
            }

            $data['sections'] = $sections;
            $data['criteria'] = $this->transformCriteriaToList($criteria);
            $data['months'] = $months;
            $this->injectResultsToCriteria($scorecardResults, $data['criteria']);

            return $data;
        } else {
            return [];
        }
    }

    private function getAllStepCriteria()
    {
        $sections = ScorecardSection::where('program_id', '=', $this->program->id)
            ->pluck('id');

        return ScorecardCriteria::whereIn('scorecard_section_id', $sections)
            ->where('dashboard_only', '!=', 1)
            ->pluck('id');
    }

    private function getStepMissingCriteria($availableIds)
    {
        $sections = ScorecardSection::where('program_id', '=', $this->program->id)
            ->where('step', '=', $this->step)
            ->pluck('id');

        return ScorecardCriteria::whereIn('scorecard_section_id', $sections)
            ->whereNotIn('id', $availableIds)
            ->where('dashboard_only', '!=', 1)
            ->get();
    }

    private function getStepSections()
    {
        return ScorecardSection::where('program_id', '=', $this->program->id)
            ->where('step', '=', $this->step)
            ->orderByRaw("CASE
            WHEN type='bronze' THEN 4
            WHEN type='silver' THEN 3
            WHEN type='gold' THEN 2
            WHEN type='ethical' THEN 1
            END")
            ->get();
    }

    private function getStepCriteria()
    {
        $sections = ScorecardSection::where('program_id', '=', $this->program->id)
            ->where('step', '=', $this->step)
            ->pluck('id');

        return ScorecardCriteria::whereIn('scorecard_section_id', $sections)
            ->where('dashboard_only', '!=', 1)
            ->pluck('id');
    }

    private function transformCriteriaToList($criteria)
    {
        ksort($criteria);
        $criteriaList = [];
        foreach ($criteria as $criterium) {
            $totalTarget = 0;
            $targets = [];
            if (!empty($criterium->target)) {
                foreach ($criterium->target as $target) {
                    $totalTarget += $target['target'];

                    $monthsCount = 1;
                    $months = [];

                    $start = new \DateTime($target['start']);
                    $end = new \DateTime($target['end']);
                    if ($target['start'] != $target['end']) {
                        $diff = $start->diff($end);
                        $monthsCount += $diff->m;
                    }

                    for ($m = intval($start->format('n')); $m <= intval($end->format('n')); $m++) {
                        $months[] = date('Y-' . (str_pad($m, 2, '0', STR_PAD_LEFT)) . '-01');
                    }

                    $targets[] = [
                        'start' => $target['start'],
                        'end' => $target['end'],
                        'months' => $months,
                        'monthsCount' => $monthsCount,
                        'target' => $target['target'],
                        'points' => false,
                        'data' => [],
                    ];
                }
            }

            $criteriaList[$criterium->id]['section_id'] = $criterium->scorecard_section_id;
            $criteriaList[$criterium->id]['total_target'] = $totalTarget;
            $criteriaList[$criterium->id]['targets'] = $targets;
            $criteriaList[$criterium->id]['label'] = $criterium->label;
            $criteriaList[$criterium->id]['tooltip'] = $criterium->tooltip;
        }

        return $criteriaList;
    }

    private function injectResultsToCriteria($results, &$criteria)
    {
        foreach ($results as $result) {
            if (isset($criteria[$result->scorecard_criteria_id])) {
                if (!empty($criteria[$result->scorecard_criteria_id]['targets'])) {
                    foreach ($criteria[$result->scorecard_criteria_id]['targets'] as $target_key => $target) {
                        if (in_array($result->month, $target['months'])) {
                            $criteria[$result->scorecard_criteria_id]['targets'][$target_key]['points'] = $result->points;
                            $criteria[$result->scorecard_criteria_id]['targets'][$target_key]['data'] = $result->data;
                        }
                    }
                }
            }
        }
    }

    public function onStepChange()
    {
        if (!empty(post('id')) && !empty(post('pharmacy'))) {
            $this->step = post('id');
            $data = $this->getData(post('pharmacy'));

            $result['html']['#scorecard-steps'] = $this->renderPartial('@steps', [
                'step' => $this->step
            ]);

            $result['html']['#stats'] = $this->renderPartial('@stats', [
                'data' => $data
            ]);

            $result['html']['#scorecard-tables'] = $this->renderPartial('@tables', [
                'data' => $data,
                'user' => $this->user,
                'pharmacy' => $this->pharmacy
            ]);

            return $result;
        }
    }
}
