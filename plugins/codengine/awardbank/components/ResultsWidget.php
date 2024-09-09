<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\ResultsWidgetSettings;
use Codengine\Awardbank\Models\ScorecardCriteria;
use Codengine\Awardbank\Models\ScorecardResult;
use Codengine\Awardbank\Models\ScorecardSection;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;

class ResultsWidget extends ComponentBase
{
    private $user;
    private $program;
    private $crm;
    private $year;
    private $month;

    public function componentDetails()
    {
        return [
            'name' => 'Results Widget',
            'description' => 'Results Widget',
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
            $this->crm = $this->getDefaultCrm();
            $this->year = $this->getDefaultYear();
            $this->month = $this->getDefaultMonth();
        }
    }

    public function onRun()
    {
        $this->coreLoadSequence();
    }

    /**
    Reusable function call the core sequence of functions to load and render the Cart partial html
     **/

    public function coreLoadSequence()
    {
        $this->generateHtml();
    }

    public function generateHtml()
    {
        $this->page['user'] = $this->user;
        $this->page['month'] = $this->month;
        $this->page['pharmacies'] = $this->getPharmacies();
        $this->page['data'] = $this->getData();
        $this->page['crm'] = $this->crm;
        $this->page['program'] = $this->program;
    }

    private function getDefaultCrm()
    {
        if ($this->user) {
            switch ($this->user->roll) {
                case 'Admin' :
                case 'Sponsor':
                case 'Symbion Staff':
                    {
                        $user = User::whereRaw('LENGTH(crm) > 0')->first();
                        return $user->crm ?? false;
                    }
                    break;
                case 'KAM' :
                    {
                        $user = User::where('kam', '=', $this->user->full_name)
                            ->whereRaw('LENGTH(crm)>0')
                            ->first();
                        return $user->crm ?? false;
                    }
                    break;
                case 'Pharmacy Member':
                case 'Member Store':
                case 'Staff Member' :
                {
                    return $this->user->crm;
                }
            }
        }

        return false;
    }

    private function getDefaultYear()
    {
        $settings = ResultsWidgetSettings::where('program_id', '=', $this->program->id)
            ->where('key', '=', 'display_year')
            ->first();

        return (!empty($settings) && !empty($settings->value)) ? $settings->value : date('Y');
    }

    private function getDefaultMonth()
    {
        $settings = ResultsWidgetSettings::where('program_id', '=', $this->program->id)
            ->where('key', '=', 'display_month')
            ->first();

        if ($settings) {
            if ($settings->value > 0) {
                return date($this->year . '-' . str_pad($settings->value, 2, '0', STR_PAD_LEFT)) . '-01';
            }
        }

        $currentMonth = intval(date('m'));
        if ($currentMonth < 3) {
            return date($this->year . '-03-01');
        } elseif ($currentMonth > 8) {
            return date($this->year . '-8-01');
        } else {
            return date($this->year . '-m-01');
        }
    }

    private function getPharmacies()
    {
        switch($this->user->roll) {
            case 'Admin' :
            case 'Sponsor':
            case 'Symbion Staff': {
                return User::whereRaw('LENGTH(crm) > 0')
                    ->groupBy('crm')
                    ->orderBy('business_name')
                    ->get();
            } break;
            case 'KAM' : {
                return User::where('kam', '=', $this->user->full_name)
                    ->whereRaw('LENGTH(crm) > 0')
                    ->groupBy('crm')
                    ->orderBy('business_name')
                    ->get();
            } break;
            case 'Pharmacy Member':
            case 'Member Store':
            case 'Staff Member' : {
                return false;
            }
        }

        return false;
    }

    private function getData()
    {
        if (!empty($this->crm)) {
            return [
                'pointsEarnedMonthly' => $this->getPointEarnedMonthly(),
                'offLocationPromotions' => $this->offLocationPromotions(),
                'loyaltyEmailMarketing' => $this->loyaltyEmailMarketing(),
                'salesGrowthHighlights' => $this->salesGrowthHighlights(),
                'educationWebinarBitesSeries' => $this->getEducationWebinarBitesSeries(),
                'totalPointsOverall' => $this->getTotalPointsOverall(),
                'totalPointsMonthly' => $this->getTotalPointsMonthly(),
                'widgetTexts' => $this->getWidgetTexts(),
            ];
        } else {
            return false;
        }
    }

    private function getPointEarnedMonthly()
    {
        $criteria = ScorecardCriteria::where('key', '=', 'S2CAT002')->first();
        if ($criteria) {
            $result = ScorecardResult::where('scorecard_criteria_id', '=', $criteria->id)
                ->where('month', '=', $this->month)
                ->where('crm', '=', $this->crm)
                ->first();

            if ($result) {
                return [
                    'points' => $result->points,
                    'data' => $result->data,
                    'floatdata' => floatval($result->data),
                    'resultColor' => floatval($result->data) >= 80 ? "green" : ( floatval($result->data) >= 70 ? "yellow" : "red" )
                ];
            }
        }

        return false;
    }

    private function offLocationPromotions()
    {
        $criteria = ScorecardCriteria::where('key', '=', 'S3MAR001')->first();
        if ($criteria) {
            $result = ScorecardResult::where('scorecard_criteria_id', '=', $criteria->id)
                ->where('month', '=', $this->month)
                ->where('crm', '=', $this->crm)
                ->first();

            if ($result) {
                $data = explode('|', $result->data);

                return [
                    'points' => $result->points,
                    'data1' => $data[0] ?? '',
                    'data2' => $data[1] ?? '',
                ];
            }
        }

        return false;
    }

    private function loyaltyEmailMarketing()
    {
        $criteria = ScorecardCriteria::where('key', '=', 'S3MAR002')->first();
        if ($criteria) {
            $result = ScorecardResult::where('scorecard_criteria_id', '=', $criteria->id)
                ->where('month', '=', $this->month)
                ->where('crm', '=', $this->crm)
                ->first();

            if ($result) {
                $data = explode('|', $result->data);

                $data1 = $data[0] ?? '';
                $data2 = $data[1] ?? '';

                return [
                    'points' => $result->points,
                    'data1' => $data1,
                    'data2' => $data2,
                    'floatdata2' => floatval($data2),
                    'data1color' => floatval($data1) >= 150 ? "green" : ( floatval($data1) >= 100 ? "yellow" : "red" ),
                    'data2color' => floatval($data2) >= 60 ? "#008000" : ( floatval($data2) >= 50 ? "#f3a400" : "#ff0000" )
                ];
            }
        }

        return false;
    }

    private function salesGrowthHighlights()
    {
        $sponsorKeys = ['S1HAL001', 'S1ASP001', 'S1RB001', 'S1BAY001'];
        $data = [];

        foreach ($sponsorKeys as $sponsorKey) {
            $criteria = ScorecardCriteria::where('key', '=', $sponsorKey)->first();
            if ($criteria) {
                $result = ScorecardResult::where('scorecard_criteria_id', '=', $criteria->id)
                    ->where('month', '=', $this->month)
                    ->where('crm', '=', $this->crm)
                    ->first();

                if ($result) {
                    $data[$sponsorKey] = [
                        'data' => $result->data,
                        'floatdata' => floatval($result->data),
                        'resultColor' => $this->getSponsorResultColor($sponsorKey, floatval($result->data))
                    ];
                }
            }
        }

        return $data;
    }

    private function getEducationWebinarBitesSeries()
    {
        $criteriaKeys = ['S4TRA001'];
        $data = ['data1' => 'N/A', 'data2' => 'N/A', 'data1Color' => 'black', 'data2Color' => 'black',];

        foreach ($criteriaKeys as $criteriaKey) {
            $criteria = ScorecardCriteria::where('key', '=', $criteriaKey)->first();
            if ($criteria) {
                $result = ScorecardResult::where('scorecard_criteria_id', '=', $criteria->id)
                    ->where('month', '=', $this->month)
                    ->where('crm', '=', $this->crm)
                    ->first();

                if ($result) {
                    $data = explode('|', $result->data);
                    $data = [
                        'data1' => $data[0] ?? 'N/A',
                        'data2' => $data[1] ?? 'N/A',
                        'data1Color' => $this->getEWBScolor($data[0] ?? 0, 0),
                        'data2Color' => $this->getEWBScolor($data[1] ?? 0, 1),
                    ];
                }
            }
        }

        return $data;
    }

    private function getEWBScolor($result, $id) {
        $result = floatval($result);
        if ($id == 0) {
            if ($result >= 1) {
                return "green";
            } else {
                return "red";
            }
        }

        if ($id == 1) {
            return "red";
        }
    }

    private function getSponsorResultColor($sponsorKey, $result) {
        switch ($sponsorKey) {
            case 'S1ASP001': {
                if ($result >= 5) {
                    return "green";
                } elseif ($result >= 4) {
                    return "yellow";
                } else {
                    return "red";
                }
            } break;
            case 'S1HAL001':
            case 'S1RB001':
            case 'S1BAY001': {
                if ($result >= 4) {
                    return "green";
                } elseif ($result >= 3) {
                    return "yellow";
                } else {
                    return "red";
                }
            } break;
        }

        return false;
    }

    private function getTotalPointsOverall()
    {
        $allStepCriteria = $this->getAllStepCriteria();
        if (empty($allStepCriteria)) {
            return 0;
        }

        $totalPoints = ScorecardResult::where('crm', '=', $this->crm)
            ->whereIn('scorecard_criteria_id', $allStepCriteria)
            ->where('month', '>=', date('Y-03-01'))
            ->where('month', '<=', date('Y-08-01'))
            ->get()
            ->sum('points');

        return $totalPoints;
    }

    private function getTotalPointsMonthly()
    {
        $allStepCriteria = $this->getAllStepCriteria();
        if (empty($allStepCriteria)) {
            return 0;
        }

        $totalPoints = ScorecardResult::where('crm', '=', $this->crm)
            ->whereIn('scorecard_criteria_id', $allStepCriteria)
            ->where('month', '=', $this->month)
            ->get()
            ->sum('points');

        return $totalPoints;
    }

    private function getAllStepCriteria()
    {
        $sections = ScorecardSection::where('program_id', '=', $this->program->id)
            ->pluck('id');

        return ScorecardCriteria::whereIn('scorecard_section_id', $sections)->pluck('id');
    }

    private function getWidgetTexts()
    {
        $key = $this->crm . '_' . $this->month . '_widget_text';
        $textRecord = ResultsWidgetSettings::where('key', '=', $key)->first();
        if ($textRecord) {
            return explode('|', $textRecord->value ?? '');
        }

        return [];
    }

    public function onChangePharmacy()
    {
        if (!empty(post('id'))) {
            $pharmacy = User::find(post('id'));
            if ($pharmacy && $pharmacy->crm) {
                $this->crm = $pharmacy->crm;
                $partial = strtolower($this->program->slug) == '5step' ? 'widget-5stepincentive' : 'widget';

                $result['html']['#results-widget'] = $this->renderPartial('@' . $partial, [
                    'user' => $this->user,
                    'month' => $this->month,
                    'pharmacies' => $this->getPharmacies(),
                    'data' => $this->getData(),
                    'crm' => $this->crm,
                    'program' => $this->program
                ]);

                return $result;
            }
        }
    }
}
