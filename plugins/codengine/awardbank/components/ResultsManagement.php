<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\LeaderboardResult;
use Codengine\Awardbank\Models\ResultsWidgetSettings;
use Codengine\Awardbank\Models\ScorecardCriteria;
use Codengine\Awardbank\Models\ScorecardResult;
use Codengine\Awardbank\Models\ScorecardResultImport;
use Codengine\Awardbank\Models\ScorecardSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use October\Rain\Exception\AjaxException;
use October\Rain\Support\Facades\Flash;
use October\Rain\Support\Facades\Input;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Response;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;

class ResultsManagement extends ComponentBase
{
    private static $limitperimport = 1000000;
    private static $batchlimitimport = 1000;

    private $user;
    private $program;
    private $manager = false;
    private $scorecardSection;
    private $step = 1;

    public $navoption;
    public $html1;
    public $html2;
    public $sectionLogoComponent;

    private static $resultRules = [
        'crm' => ['required', 'crm_exists'], 'points' => [''], 'data' => ['required'], 'criteria_key' => ['required', 'criteria_exists'],
    ];

    private static $importFileColumnsMapping = [
        'CRM' => 'crm', 'Points' => 'points', 'Data' => 'data', 'CRTID' => 'criteria_key',
    ];

    public function componentDetails()
    {
        return [
            'name' => 'Results Management',
            'description' => 'Results Management',
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
            $this->manager = $this->user->currentProgram->checkIfManager($this->user);
            $this->scorecardSection = $this->getStepDefaultSection($this->step);
        }

        $this->setNavOption();
        $this->bindLogoComponent();
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

    private function setNavOption()
    {
        if (empty($this->navoption)) {
            $this->navoption = post('navoption') ?? ($this->param('navoption') ?? 'default');
        }

        if ($this->navoption == 'default') {
            $this->navoption = 'scoreboard';
        }
    }

    public function generateHtml()
    {
        $this->page['manager'] = $this->manager;
        $this->html1 = $this->renderPartial('@programsettingsnav',
            [
                'navoption' => 'results-management-' . $this->navoption,
                'user' => $this->user,
                'program' => $this->program,
                'is_results_management_page' => true,
            ]
        );

        $this->html2 = $this->renderPartial('@' . $this->navoption, $this->getPartialData());
    }

    private function getPartialData()
    {
        $data = [
            'sections' => ScorecardSection::where('step', '=' , $this->step)->get(),
            'step' => $this->step,
            'stepLabel' => $this->getStepLabel($this->step),
            'current_section' => $this->scorecardSection,
        ];

        if ($this->navoption == 'scoreboard' || $this->navoption == 'default') {
            $imports = ScorecardResultImport::orderBy('id', 'desc')->take(10)->get();
            foreach($imports as $import) {
                $import->status = ScorecardResultImport::getStatusLabel($import->status);
            }

            $data['imports'] = $imports;
        }

        if ($this->navoption == 'leaderboard') {
            $settings = ResultsWidgetSettings::where('program_id', '=', $this->program->id)
                ->whereIn('key', ['leaderboard_display_month', 'leaderboard_display_points'])
                ->pluck('value', 'key')
                ->toArray();

            $data['leaderboard_settings'] = [
              'display_month' => $settings['leaderboard_display_month'] ?? '5',
              'display_points' => $settings['leaderboard_display_points'] ?? 'off',
            ];
        }

        return $data;
    }

    private function getStepDefaultSection($step)
    {
        if ($step == 1) {
            //Session::forget('resultsManagement::default_scorecardSection');
            if ($defaultScorecardSectionId = Session::get('resultsManagement::default_scorecardSection')) {
                if ($defaultScorecardSection = ScorecardSection::find($defaultScorecardSectionId)) {
                    return $defaultScorecardSection;
                }
            }
        }

        $scorecardSection = ScorecardSection::where('step', '=', $step)
            ->orderBy('id', 'desc')
            ->first();
        //If there's no section defined and step != 1 create the default section as the user is not able to do so
        if (!$scorecardSection && $step != 1) {
            $scorecardSection = new ScorecardSection();
            $scorecardSection->program_id = $this->program->id;
            $scorecardSection->step = $step;
            $scorecardSection->name = 'Default';
            $scorecardSection->type = 'default';
            $scorecardSection->save();
        }

        return $scorecardSection;
    }

    private function getStepLabel($step)
    {
        switch ($step) {
            case 1 : return 'Buying Power';
            case 2 : return 'Category Management';
            case 3 : return 'Multi-Channel Marketing';
            case 4 : return 'Upskill Your Staff';
            case 5 : return 'Business Development & Support';
            default : return '';
        }
    }

    private function bindLogoComponent()
    {
        if ($this->scorecardSection) {
            $this->sectionLogoComponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'sectionLogo',
                [
                    'deferredBinding' => false
                ]
            );

            $this->sectionLogoComponent->bindModel('logo', $this->scorecardSection);
        }
    }

    public function onStepChange()
    {
        if (!empty(post('id'))) {
            $this->step = post('id');
            $scorecardSection = $this->getStepDefaultSection($this->step);

            $result['html']['#html2target'] = $this->renderPartial('@scoreboard', [
                'manager' => $this->manager,
                'sections' => ScorecardSection::where('step', '=' , $this->step)->get(),
                'step' => $this->step,
                'stepLabel' => $this->getStepLabel($this->step),
                'current_section' => $scorecardSection,
            ]);

            return $result;
        }
    }

    public function onNewSection()
    {
        if (empty(post('section-name')) || empty(request('section-type'))) {
            $result['manualerror'] = 'Section name & type are required.';
            return $result;
        }

        try {
            $scorecardSection = new ScorecardSection();
            $scorecardSection->program_id = $this->program->id;
            $scorecardSection->step = post('step');
            $scorecardSection->name = post('section-name');
            $scorecardSection->type = post('section-type');

            if ($scorecardSection->save()) {
                $this->scorecardSection = $scorecardSection;
                Session::put('resultsManagement::default_scorecardSection', $this->scorecardSection->id);
                $this->bindLogoComponent();

                $result['updatesucess'] = 'Section has been successfully created.';
                $result['html']['#section-dropdown'] = $this->renderPartial('@sectiondropdown', [
                    'sections' => ScorecardSection::all(),
                    'current_section' => $scorecardSection,
                ]);
                $result['html']['#section-logo-uploader'] = $this->renderPartial('@sectionlogo', [

                ]);
                $result['html']['#section-criteria-wrapper'] = $this->renderPartial('@sectioncriteria', [
                    'current_section' => $scorecardSection,
                ]);
                return $result;
            }

        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    public function onChangeSection()
    {
        if (empty(post('id'))) {
            $result['manualerror'] = 'Invalid section selected.';
            return $result;
        }

        try {
            $scorecardSection = ScorecardSection::find(post('id'));
            if ($scorecardSection) {
                $this->scorecardSection = $scorecardSection;
                $result['html']['#section-criteria-wrapper'] = $this->renderPartial('@sectioncriteria', [
                    'current_section' => $scorecardSection,
                ]);

                Session::put('resultsManagement::default_scorecardSection', $this->scorecardSection->id);
                $this->bindLogoComponent();
                $result['html']['#section-logo-uploader'] = $this->renderPartial('@sectionlogo');

                return $result;
            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    public function onNewSectionCriteria()
    {
        try {
            $scorecardSection = ScorecardSection::find(post('section_id'));
            if ($scorecardSection) {
                $sectionCriteria = new ScorecardCriteria();
                $sectionCriteria->scorecard_section_id = $scorecardSection->id;
                $sectionCriteria->key = post('key');
                $sectionCriteria->label = post('label');
                $sectionCriteria->tooltip = post('tooltip');
                $sectionCriteria->dashboard_only = post('dashboard_only');
                $sectionCriteria->target = [];
                if ($sectionCriteria->save()) {
                    $result['updatesucess'] = 'Criteria has been successfully created.';
                    $result['html']['#section-criteria-wrapper'] = $this->renderPartial('@sectioncriteria', [
                        'current_section' => $scorecardSection,
                    ]);
                    return $result;
                } else {
                    $result['manualerror'] = 'Error while creating criteria';
                    return $result;
                }
            } else {
                $result['manualerror'] = 'Invalid section provided.';
                return $result;
            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    public function onEditSectionCriteria()
    {
        if (!empty(post('id'))) {
            $scorecardCriteria = ScorecardCriteria::find(post('id'));
            if ($scorecardCriteria) {
                $result['html']['#section-criteria-wrapper'] = $this->renderPartial('@sectioncriteriaform', [
                    'criteria' => $scorecardCriteria
                ]);
                return $result;
            }
        }
    }

    public function onDeleteSectionCriteria()
    {
        if (!empty(post('id'))) {
            $scorecardCriteria = ScorecardCriteria::find(post('id'));
            if ($scorecardCriteria->delete()) {
                $result['updatesucess'] = 'Criteria has been successfully deleted.';
                $result['html']['#section-criteria-wrapper'] = $this->renderPartial('@sectioncriteria', [
                    'current_section' => $scorecardCriteria->scorecard_section,
                ]);
                return $result;
            } else {
                $result['manualerror'] = 'Error while deleting criteria';
                return $result;
            }
        } else {
            $result['manualerror'] = 'Invalid criteria provided.';
            return $result;
        }
    }

    public function onUpdateSectionCriteria()
    {
        try {
            $scorecardCriteria = ScorecardCriteria::find(post('id'));
            if ($scorecardCriteria) {
                $scorecardCriteria->key = post('key');
                $scorecardCriteria->label = post('label');
                $scorecardCriteria->tooltip = post('tooltip');
                $scorecardCriteria->dashboard_only = post('dashboard_only');
                if ($scorecardCriteria->save()) {
                    $result['updatesucess'] = 'Criteria has been successfully updated.';
                    $result['html']['#section-criteria-wrapper'] = $this->renderPartial('@sectioncriteria', [
                        'current_section' => $scorecardCriteria->scorecard_section,
                    ]);
                    return $result;
                } else {
                    $result['manualerror'] = 'Error while updating criteria';
                    return $result;
                }
            } else {
                $result['manualerror'] = 'Invalid criteria provided.';
                return $result;
            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    public function onNewSectionCriteriaTarget()
    {
        try {
            $scorecardCriteria = ScorecardCriteria::find(post('id'));
            if ($scorecardCriteria) {
                if ((empty(post('target')) && post('target')!=='0') || empty(post('start')) || empty(post('end'))) {
                    $result['manualerror'] = 'Start, End & Target are required.';
                    return $result;
                }

                $start = intval(post('start'));
                $end = intval(post('end'));

                if ($start > 8 || $start < 3) {
                    $result['manualerror'] = 'Period Start month needs to be between March and August';
                    return $result;
                }

                if ($end > 8 || $end < 3) {
                    $result['manualerror'] = 'Period End month needs to be between March and August';
                    return $result;
                }

                $startDate = date('Y'). '-' . (str_pad($start, 2, '0', STR_PAD_LEFT)) . '-01';
                $endDate = date('Y'). '-' . (str_pad($end, 2, '0', STR_PAD_LEFT)) . '-01';

                //Check whether there date interval collide with other targets
                $collide = $this->checkTargetConflicts($scorecardCriteria, $startDate, $endDate);
                if ($collide) {
                    $result['manualerror'] = $collide['error'];
                    return $result;
                }

                $target = $scorecardCriteria->target;
                $target[] = [
                  'start' => $startDate,
                  'end' => $endDate,
                  'target' => floatval(post('target'))
                ];
                $scorecardCriteria->target = $target;

                if ($scorecardCriteria->save()) {
                    $result['updatesucess'] = 'Criteria targets have been successfully updated.';
                    $result['html']['#criteria-targets'] = $this->renderPartial('@sectioncriteriatargets', [
                        'criteria' => $scorecardCriteria,
                    ]);
                    return $result;
                } else {
                    $result['manualerror'] = 'Error while updating criteria';
                    return $result;
                }
            } else {
                $result['manualerror'] = 'Invalid criteria provided.';
                return $result;
            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    private function checkTargetConflicts($scorecardCriteria, $startDate, $endDate)
    {
        $collide = false;
        $errors = [];
        if (!empty($scorecardCriteria->target)) {
            foreach ($scorecardCriteria->target as $scTarget) {
                if (strtotime($startDate) <= strtotime($scTarget['start'])) {
                    $collide = true;
                    $errors[] = (
                        'Period start ' . (date('F', strtotime($startDate)))
                        . ' is already included in '. (date('F', strtotime($scTarget['start'])))
                        . ' - ' . (date('F', strtotime($scTarget['end'])))
                        . ' target period.'
                    );
                }

                if (strtotime($endDate) <= strtotime($scTarget['end'])) {
                    $collide = true;
                    $errors[] = (
                        'Period end ' . (date('F', strtotime($endDate)))
                        . ' is already included in '. (date('F', strtotime($scTarget['start'])))
                        . ' - ' . (date('F', strtotime($scTarget['end'])))
                        . ' target period.'
                    );
                }

                if (strtotime($startDate) <= strtotime($scTarget['end'])) {
                    $collide = true;
                    $errors[] = (
                        'Period start ' . (date('F', strtotime($startDate)))
                        . ' is already included in '. (date('F', strtotime($scTarget['start'])))
                        . ' - ' . (date('F', strtotime($scTarget['end'])))
                        . ' target period.'
                    );
                }
            }
        }

        if ($collide) {
            return [
              'error' => $errors[0] ?? 'Invalid Period Start & End.'
            ];
        }

        return $collide;
    }

    public function onImport()
    {
        if ($this->manager) {
            if (empty(post('month'))) {
                //TODO
                //return invalid month
            }

            if (Input::has('file')) {
                try {
                    $fileName = md5_file(Input::file('file')->getRealPath()) . '.csv';
                    $fileRowsCount = count(file(Input::file('file')->getRealPath()));
                    if ($fileRowsCount > 200000) {
                        throw new \Exception('File has more then 200,000 records. Please reduce the number of records & make sure there are no empty records included.');
                    }

                    $upload = $this->uploadFileToS3Bucket(Input::file('file')->getRealPath(), $fileName);

                    if ($upload) {
                        $import = new ScorecardResultImport();
                        $import->program_id = $this->program->id;
                        $import->status = ScorecardResultImport::STATUS_PENDING;
                        $import->month = date('Y') . '-' . sprintf('%02d', (int)post('month')) . '-01';
                        $import->skip_errors = Input::has('skip_errors') ?
                            (Input::get('skip_errors') == 'on' ? 1 : 0) : 0;
                        $import->source_file_path = $fileName;
                        $import->total = $this->getFileRecordsCount(Input::file('file')->getRealPath());
                        $import->processed = 0;
                        $import->save();
                    } else {
                        $errors['file_errors'][] = 'Error while uploading file.';
                    }
                } catch (\Exception $e) {
                    //error_log('Error while uploading scorecard file ::' . $e->getMessage());
                    $errors['file_errors'][] = $e->getMessage();
                }

                if (!empty($errors)) {
                    $this->page['import_errors'] = $errors;
                } else {
                    $this->page['import_stats'] = [
                        'File successfully uploaded. Your import will be processed shortly.'
                    ];
                }
            } else {
                $errors['file_errors'][] = 'Invalid source file';
                $this->page['import_errors'] = $errors;
            }
        }
    }

    private function uploadFileToS3Bucket($filepath, $fileName){
        $file = file_get_contents($filepath);
        return \Illuminate\Support\Facades\Storage::disk('s3')->put('uploads/imports/' . $fileName, $file);
    }

    private function getFileRecordsCount($filepath)
    {
        $records = array_map('str_getcsv', file($filepath));
        //Do`nt count the header row
        return empty($records) ? 0 : (count($records) - 1);
    }

    public static function processScorecardImportFile($scorecardImport)
    {
        $records = self::parseCsvFile($scorecardImport);
        $errors = self::validateResultRecords($scorecardImport, $records);

        //On first run delete previous data included in the file
        //Only if the new file is valid and has valid data
        if ($scorecardImport->data_truncated == 0 && $scorecardImport->status == 0 && $scorecardImport->processed == 0) {
            if (empty($errors['file_errors'])) {
                if (empty($errors['data_errors']) || $scorecardImport->skip_errors) {
                    self::deletePreviousScorecardResults($scorecardImport);
                }
            }
        }

        $resultStatus = false;
        if (!empty($errors['file_errors'])) {
            if ($scorecardImport->status == 0) {
                $resultStatus = ScorecardResultImport::STATUS_FILE_HAS_ERRORS;
            } else {
                $resultStatus = ScorecardResultImport::STATUS_NEEDS_REVIEW;
            }
        } elseif (!empty($errors['data_errors']) && !$scorecardImport->skip_errors) {
            $resultStatus = ScorecardResultImport::STATUS_INVALID_RECORDS;
        }

        if ($resultStatus) {
            $scorecardImport->status = $resultStatus;
            if ($resultStatus != ScorecardResultImport::STATUS_NEEDS_REVIEW) {
                $scorecardImport->skipped = $scorecardImport->total;
                $scorecardImport->processed = 0;
            }
            $scorecardImport->processing = 0;
            Log::debug('Processing being set to 0');
            $scorecardImport->errors = array_slice($errors, 0, 50);
            $scorecardImport->save();
            Log::debug('Processing set to 0');
            return;
        }

        //If there are no file errors AND skip errors is allowed
        if (empty($errors['file_errors']) && (empty($errors['data_errors']) || $scorecardImport->skip_errors)) {
            $stats = self::importRecords($scorecardImport->program_id, $records, $errors, $scorecardImport->month);
            $processedTotal = $stats['processed'] + $stats['skipped'];

            if ($scorecardImport->total <= ($scorecardImport->processing_current_offset + $processedTotal)) {
                $scorecardImport->status = ScorecardResultImport::STATUS_PROCESSED;
            } else {
                $scorecardImport->status = ScorecardResultImport::STATUS_IN_PROGRESS;
                $scorecardImport->processing_current_offset = $scorecardImport->processing_current_offset + $processedTotal;
            }

            $scorecardImport->processing = 0;
            Log::debug('Processing being set to 0');
            $scorecardImport->processed = $scorecardImport->processed + $stats['processed'];
            $scorecardImport->skipped = $scorecardImport->skipped + $stats['skipped'];

            if (empty($scorecardImport->errors)) {
                $scorecardImport->errors = array_slice($errors, 0, 50);
            } else {
                $scorecardImport->errors = [
                    'file_errors' => [],
                    'data_errors' => array_merge(
                        $scorecardImport->errors['data_errors'],
                        array_slice($errors['data_errors'], 0, 50)
                    )
                ];
            }

            $scorecardImport->save();
            Log::debug('Processing set to 0');
        }
    }

    private static function deletePreviousScorecardResults(ScorecardResultImport $scorecardImport)
    {
        try {
            for ($i = 0; $i < ceil($scorecardImport->total / self::$batchlimitimport); $i++) {
                $criteriaKeys = $crmIds = [];
                $records = self::parseCsvFile($scorecardImport, ($i * self::$batchlimitimport));

                if (!empty($records)) {
                    foreach ($records as $record) {
                        if (!empty($record['CRTID']) && $record['CRTID'] != 'CRTID' && !in_array($record['CRTID'], $criteriaKeys)) {
                            $criteriaKeys[] = $record['CRTID'];
                        }

                        if (!empty($record['CRM']) && $record['CRM'] != 'CRM' && !in_array($record['CRM'], $criteriaKeys)) {
                            $crmIds[] = $record['CRM'];
                        }
                    }
                }

                if (!empty($criteriaKeys)) {
                    $scoreboardCardIds = ScorecardCriteria::whereIn('key', $criteriaKeys)->pluck('id')->toArray();
                    if (!empty($scoreboardCardIds)) {
                        ScorecardResult::where('month', '=', $scorecardImport->month)
                            ->whereIn('scorecard_criteria_id', $scoreboardCardIds)
                            ->whereIn('crm', $crmIds)
                            ->delete();
                    }
                }
            }

            $scorecardImport->data_truncated = 1;
            $scorecardImport->save();

        } catch (\Exception $e) {
            $scorecardImport->data_truncated = false;
            $scorecardImport->processing = 0;
            $scorecardImport->save();
            Log::error($e->getMessage() . ' ' . $e->getTraceAsString());
        }
    }

    private static function parseCsvFile(ScorecardResultImport $scorecardImport, $custom_offset = false)
    {
        $fileContent = Storage::disk('s3')->get('/uploads/imports/' . $scorecardImport->source_file_path);

        try {
            $recordsArray = preg_split('/\r*\n+|\r+/', $fileContent);
            //Remove empty lines
            $records = array_filter($recordsArray, function($a) {
                $is_empty = empty(str_replace([',', ';'], ['', ''], $a));
                return $is_empty ? false : str_getcsv($a);
            });
            $records = array_map("str_getcsv", $records);

            $header = $records[0] ?? [];

            //Apply current offset
            //If first attempt, increase position by 1 to skip the header
            $offset = 1 + ($custom_offset ? $custom_offset : $scorecardImport->processing_current_offset);
            $limit = self::$batchlimitimport;
            $records = array_slice($records, $offset, $limit, false);

            if (!empty( $records)) {
                array_walk($records, function (&$a) use ($header) {
                    if (count($header) == count($a)) {
                        $a = array_combine($header, $a);
                        if (!empty($a['CRTID'])) {
                            $a['CRTID'] = trim($a['CRTID']);
                        }
                        if (!empty($a['CRM'])) {
                            $a['CRM'] = trim($a['CRM']);
                        }
                    }
                });
                //array_shift($records); # remove column header
            }
        } catch (\Exception $e) {
            $scorecardImport->processing = 0;
            Log::debug('Processing set to 0');
            $scorecardImport->save();
            Log::error($e->getMessage() . ' ' . $e->getTraceAsString());
            return [];
        }

        return $records;
    }

    private static function validateResultRecords(ScorecardResultImport $scorecardImport, $records)
    {
        $errors = [
            'file_errors' => [],
            'data_errors' => []
        ];

        if (empty($records)) {
            $errors['file_errors'][] = 'No valid records detected.';
            return $errors;
        }

        if (count($records) > self::$limitperimport) {
            $errors['file_errors'][] = 'Your source file contains more then ' . self::$limitperimport . ' records (' . count($records) . '). Please split the records into multiple files and try again.';
        }

        //Check columns - All records have the same columns so we only need to check one
        $flippedImportFileColumnsMapping = array_flip(self::$importFileColumnsMapping);
        $last_record = $records[count($records) - 1];
        if (is_array($last_record)) {
            $last_record_columns = array_keys($last_record);
            foreach (self::$resultRules as $required_column => $rules) {
                if (!in_array($flippedImportFileColumnsMapping[$required_column], $last_record_columns)) {
                    $errors['file_errors'][] = 'Column '
                        . $flippedImportFileColumnsMapping[$required_column]
                        . ' is missing. Please add it to your source file or check the current file for misspelling.';
                }
            }

            if (empty($errors['file_errors'])) {
                $scoreboardCriteriaIds = ScorecardCriteria::all()->pluck('key')->toArray();
                $crmIds = User::whereNotNull('crm')->where('crm', '!=', '')->get()->pluck('crm')->toArray();
                //Check each record attributes
                $index = $scorecardImport->processing_current_offset + 1;
                foreach ($records as $key => $record) {
                    $recordErrors = [];
                    foreach ($record as $attr => $value) {
                        if (!empty(self::$importFileColumnsMapping[$attr])) {
                            if (!empty(self::$resultRules[self::$importFileColumnsMapping[$attr]])) {
                                foreach (self::$resultRules[self::$importFileColumnsMapping[$attr]] as $rule) {
                                    switch ($rule) {
                                        case 'required' :
                                            {
                                                if (empty($value) && $value !== '0') {
                                                    $recordErrors[] = 'Record ' . $index . ' has invalid ' . $attr . ' value.';
                                                }
                                            }
                                            break;
                                        case 'criteria_exists' :
                                            {
                                                if (!in_array($value, $scoreboardCriteriaIds)) {
                                                    $recordErrors[] = 'Record ' . $index . ' has invalid ' . $attr . ' value (' . $value . ')';
                                                }
                                            }
                                            break;
                                        case 'crm_exists' :
                                            {
                                                if (!in_array($value, $crmIds)) {
                                                    $recordErrors[] = 'Record ' . $index . ' has invalid ' . $attr . ' value (' . $value . ')';
                                                }
                                            }
                                            break;
                                    }
                                }
                            }
                        } else {
                            $recordErrors[] = 'Invalid record';
                        }
                    }

                    if (!empty($recordErrors)) {
                        $errors['data_errors'][$key] = $recordErrors;
                    }

                    $index++;
                }
            }
        } else {
            $errors['file_errors'][] = 'No valid records detected.';
        }

        return $errors;
    }

    private static function importRecords($programId, $records, $errors, $month)
    {
        $stats = [];
        $totalFailed = $totalImported = 0;
        $programSectionIds = ScorecardSection::where('program_id', '=' , $programId)->pluck('id');

        //Delete only those sections that are included in the source file
        $criteriaKeys = [];
        foreach($records as $record) {
            if (!empty($record['CRTID']) && !in_array($record['CRTID'], $criteriaKeys)) {
                $criteriaKeys[] = $record['CRTID'];
            }
        }

        $scoreboardCriteriaIds = ScorecardCriteria::whereIn('scorecard_section_id', $programSectionIds)
            ->whereIn('key', $criteriaKeys)
            ->pluck('id', 'key')
            ->toArray();

        foreach ($records as $key => $record) {
            //Skip record if it has errors
            if (!empty($errors['data_errors'][$key])) {
                $stats[] = implode(', ', $errors['data_errors'][$key]) . ' Record has not been imported.';
                $totalFailed++;
                continue;
            } else {
                $result = new ScorecardResult();
                $result->scorecard_criteria_id = $scoreboardCriteriaIds[$record['CRTID']] ?? null;
                $result->month = $month;
                $result->crm = $record['CRM'];
                $result->points = intval($record['Points'] ?? 0);
                $result->data = $record['Data'];
                if ($result->save()) {
                    $totalImported++;
                } else {
                    $totalFailed++;
                }
            }
        }

        $stats['total'] = count($records);
        $stats['processed'] = $totalImported;
        $stats['skipped'] = $totalFailed;

        return $stats;
    }

    public function onDownloadImportErrors()
    {
        if ($this->manager) {
            try {
                $resultImport = ScorecardResultImport::find(post('id'));
                if ($resultImport) {
                    return $this->generateImportErrorsCsvReport($resultImport);
                } else {
                    $result['manualerror'] = 'Invalid Import Id.';
                    return $result;
                }
            } catch (\Exception $ex) {
                $result['manualerror'] = $ex->getMessage();
                return $result;
            }
        }
    }

    private function generateImportErrorsCsvReport(ScorecardResultImport $resultImport)
    {
        $filename = storage_path('/csv/export/ImportErrorsReport.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Type',
            'Error'
        ];

        fputcsv($handle, $outputarray);

        if (!empty($resultImport->errors['file_errors'])) {
            foreach ($resultImport->errors['file_errors'] as $fileError) {
                fputcsv($handle, [
                    'File',
                    $fileError
                ]);
            }
        }

        if (!empty($resultImport->errors['data_errors'])) {
            foreach ($resultImport->errors['data_errors'] as $row => $rowErrors) {
                foreach ($rowErrors as $rowError) {
                    fputcsv($handle, [
                        'Data',
                        $rowError
                    ]);
                }
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download($filename, 'ImportErrorsReport.csv', $headers);
    }

    public function onWidgetSettingsUpdate()
    {
        if (empty(post('display_year')) || empty(post('display_month'))) {
            Flash::error("Invalid request.");
        } else {
            $setting_keys = ['display_year', 'display_month'];
            $processed = 0;
            foreach ($setting_keys as $setting_key) {
                $setting = ResultsWidgetSettings::where('program_id', '=', $this->program->id)
                    ->where('key', '=', $setting_key)
                    ->first();

                if ($setting) {
                    $setting->value = post($setting_key);
                } else {
                    $setting = new ResultsWidgetSettings();
                    $setting->key = $setting_key;
                    $setting->program_id = $this->program->id;
                    $setting->value = post($setting_key);
                }

                if ($setting->save()) {
                    $processed++;
                }
            }

            if ($processed == count($setting_keys)) {
                Flash::success("Settings saved.");
            } else {
                Flash::error("Error while saving settings.");
            }
        }
    }

    private function parseSimpleCsvFile($filepath)
    {
        $records = array_map('str_getcsv', file($filepath));
        array_walk($records, function(&$a) use ($records) {
            if (count($records[0]) != count($a)) {
                return;
            }
            $a = array_combine($records[0], $a);
        });
        array_shift($records); # remove column header
        return $records;
    }

    public function onDashboardKeyResultsImport()
    {
        if (empty(post('import_key_result_year')) || empty(post('import_key_result_month'))) {
            $this->page['import_errors'] = ['Invalid request.'];
            return;
        }

        if (!$this->verifyKeyResultCriteria()) {
            $this->page['import_errors'] = [
                'file_errors' => [
                    "One of the following criteria hasn't been set up yet: S1HAL001, S1BAY001, S1ASP001, S1RB001"
                ]
            ];
            return;
        }

        if (Input::has('file')) {
            $path = Input::file('file')->getRealPath();
            $records = $this->parseSimpleCsvFile($path);

            $errors = [
                'file_errors' => [],
                'data_errors' => []
            ];

            if (empty($records)) {
                $errors['file_errors'][] = 'No valid records detected.';
            }

            $columnsToCheck = [
                'CRM' => 'CRMID',
                'Haleon Sales Growth' => 'Haleon Sales Growth',
                'Bayer Sales Growth' => 'Bayer Sales Growth',
                'Aspen Sales Growth' => 'Aspen Sales Growth',
                'Reckitt Sales Growth' => 'Reckitt Sales Growth'
            ];

            $valid_header_columns_count = 0;
            foreach ($columnsToCheck as $key => $headerLabel) {
                if (in_array($headerLabel, array_keys($records[0]))) {
                    $valid_header_columns_count++;
                }
            }

            if ($valid_header_columns_count != count($columnsToCheck)) {
                $errors['file_errors'][] = 'Your source file needs to have these columns: ' . implode(', ', $columnsToCheck);
            }

            //If there are no file errors
            if (empty($errors['file_errors']) && (empty($errors['data_errors']) || Input::has('skip_errors'))) {
                $month = (int)post('import_key_result_year') . '-'
                    . sprintf('%02d', (int)post('import_key_result_month')) . '-01';
                $this->page['import_stats'] = $this->importWidgetKeyResults($records, $errors, $month);
            } else {
                $this->page['import_errors'] = $errors;
            }
        } else {
            $errors['file_errors'][] = 'Invalid source file';
            $this->page['import_errors'] = $errors;
        }
    }

    private function verifyKeyResultCriteria()
    {
        //TODO
        //You need to figure out the criteria mapping
        //S1HAL001 - FOR HALEON
        //S1BAY001 - FOR BAYER
        //S1ASP001 - FOR ASPEN
        //S1RB001 - FOR RECKIT
        //Check whether all of the required criteria exist
        $test = ScorecardCriteria::whereIn('key', ['S1HAL001', 'S1BAY001', 'S1ASP001', 'S1RB001'])->get()->count();
        return $test === 4;
    }

    private function importWidgetKeyResults($records, $errors, $month)
    {
        $stats = [];
        $totalFailed = $totalImported = 0;
        //Get criteria ID's
        $criteriaIDs = ScorecardCriteria::whereIn('key', ['S1HAL001', 'S1BAY001', 'S1ASP001', 'S1RB001'])
            ->get()
            ->pluck('id', 'key');

        $columnToCriteriaMapping = [
            'S1HAL001' => 'Haleon Sales Growth',
            'S1BAY001' => 'Bayer Sales Growth',
            'S1ASP001' => 'Aspen Sales Growth',
            'S1RB001' => 'Reckitt Sales Growth',
        ];

        foreach ($records as $key => $record) {
            //Skip record if it has errors
            if (isset($errors['data_errors'][$key])) {
                $stats[] = implode(', ', $errors['data_errors'][$key]) . ' Record has not been imported.';
                $totalFailed++;
                continue;
            } else {
                foreach ($columnToCriteriaMapping as $criteria_key => $column_name) {
                    $value = $record[$column_name] ?? null;
                    if (!empty($value) && !empty($criteriaIDs[$criteria_key]) && !empty($record["CRMID"])) {
                        $result = ScorecardResult::where('month', '=', $month)
                            ->where('crm', '=', $record['CRMID'])
                            ->where('scorecard_criteria_id', '=', $criteriaIDs[$criteria_key])
                            ->first();

                        if (empty($result)) {
                            $result = new ScorecardResult();
                            $result->scorecard_criteria_id = $criteriaIDs[$criteria_key] ?? null;
                            $result->month = $month;
                            $result->crm = $record["CRMID"];
                        }

                        $result->points = 0;
                        $result->data = $value;
                        $result->save();

                        $totalImported++;
                    } else {
                        $totalFailed++;
                    }
                }
            }
        }

        $stats[] = 'Total successfully imported (processed) records: ' . $totalImported;
        $stats[] = 'Total skipped records: ' . $totalFailed;

        return $stats;
    }

    public function onWidgetTextImport()
    {
            if (empty(post('month'))) {
                Flash::error("Invalid request.");
            }

            if (Input::has('file')) {
                $path = Input::file('file')->getRealPath();
                $records = $this->parseSimpleCsvFile($path);

                $errors = [
                    'file_errors' => [],
                    'data_errors' => []
                ];

                if (empty($records)) {
                    $errors['file_errors'][] = 'No valid records detected.';
                }

                if (!in_array('CRM', array_keys($records[0])) || !in_array('Text', array_keys($records[0]))) {
                    $errors['file_errors'][] = 'Your source file needs to have these columns: CRM, Text';
                }

                $columnsToCheck = [
                    'CRM' => 'CRM',
                    'Text' => 'Text'
                ];

                $index = 1;
                foreach ($records as $key => $record) {
                    foreach ($columnsToCheck as $columnKey => $column) {
                        if (empty($record[$columnKey])) {
                            $errors['data_errors'][$key][] = 'Record ' . $index . ' has invalid ' . $columnKey . ' value.';
                        }
                    }
                    $index++;
                }

                //If there are no file errors AND
                if (empty($errors['file_errors']) && (empty($errors['data_errors']) || Input::has('skip_errors'))) {
                    $month = date('Y') . '-' . sprintf('%02d', (int)post('month')) . '-01';
                    $this->page['import_stats'] = $this->importWidgetTextRecords($records, $errors, $month);
                } else {
                    $this->page['import_errors'] = $errors;
                }
            } else {
                $errors['file_errors'][] = 'Invalid source file';
                $this->page['import_errors'] = $errors;
            }
    }

    private function importWidgetTextRecords($records, $errors, $month)
    {
        $stats = [];
        $totalFailed = $totalImported = 0;

        foreach ($records as $key => $record) {
            //Skip record if it has errors
            if (isset($errors['data_errors'][$key])) {
                $stats[] = implode(', ', $errors['data_errors'][$key]) . ' Record has not been imported.';
                $totalFailed++;
                continue;
            } else {
                $key = $record['CRM']. '_' . $month . '_widget_text';
                $widgetSettingsRecord =  ResultsWidgetSettings::where('key', '=', $key)->first();
                if ($widgetSettingsRecord) {
                    $widgetSettingsRecord->value = $record['Text'];
                    if ($widgetSettingsRecord->save()) {
                        $totalImported++;
                    } else {
                        $totalFailed++;
                    }
                } else {
                    $widgetTextSetting = new ResultsWidgetSettings();
                    $widgetTextSetting->program_id = $this->program->id;
                    $widgetTextSetting->key = $key;
                    $widgetTextSetting->value = $record['Text'];
                    if ($widgetTextSetting->save()) {
                        $totalImported++;
                    } else {
                        $totalFailed++;
                    }
                }
            }
        }

        $stats[] = 'Total successfully imported (processed) records: ' . $totalImported;
        $stats[] = 'Total skipped records: ' . $totalFailed;

        return $stats;
    }

    public function onLeaderboardImport()
    {
        if (empty(post('month')) || empty(post('section'))) {
            //TODO
            //return invalid month
        }

        if (Input::has('file')) {
            $path = Input::file('file')->getRealPath();
            $records = $this->parseSimpleCsvFile($path);

            $errors = [
                'file_errors' => [],
                'data_errors' => []
            ];

            if (empty($records)) {
                $errors['file_errors'][] = 'No valid records detected.';
            }

            if (!in_array('CRM', array_keys($records[0])) || !in_array('Points', array_keys($records[0]))) {
                $errors['file_errors'][] = 'Your source file needs to have these columns: Pharmacy Name, Points';
            }

            $columnsToCheck = [
                'CRM' => 'CRM',
                'Points' => 'Points'
            ];

            $index = 1;
            foreach ($records as $key => $record) {
                foreach ($columnsToCheck as $columnKey => $column) {
                    if (empty($record[$columnKey]) && $record[$columnKey] != 0) {
                        $errors['data_errors'][$key][] = 'Record ' . $index . ' has invalid ' . $columnKey . ' value.';
                    }
                }
                $index++;
            }

            //If there are no file errors AND
            if (empty($errors['file_errors']) && (empty($errors['data_errors']) || Input::has('skip_errors'))) {
                $month = date('Y') . '-' . sprintf('%02d', (int)post('month')) . '-01';
                $section = post('section');
                $this->page['import_stats'] = $this->importLeaderboardRecords($records, $errors, $month, $section);
            } else {
                $this->page['import_errors'] = $errors;
            }
        } else {
            $errors['file_errors'][] = 'Invalid source file';
            $this->page['import_errors'] = $errors;
        }
    }

    private function importLeaderboardRecords($records, $errors, $month, $section)
    {
        $stats = [];
        $totalFailed = $totalImported = 0;

        //Delete all previous data
        LeaderboardResult::where('program_id', '=', $this->program->id)
            ->where('section', '=', $section)
            ->where('month', '=', $month)
            ->delete();

        foreach ($records as $key => $record) {
            //Skip record if it has errors
            if (isset($errors['data_errors'][$key])) {
                $stats[] = implode(', ', $errors['data_errors'][$key]) . ' Record has not been imported.';
                $totalFailed++;
                continue;
            } else {
                $result = new LeaderboardResult();
                $result->month = $month;
                $result->program_id = $this->program->id;
                $result->section = $section;
                $result->crm = $record['CRM'];
                $result->points = $record['Points'] ?? 0;

                if ($result->save()) {
                    $totalImported++;
                } else {
                    $totalFailed++;
                }
            }
        }

        $stats[] = 'Total successfully imported (processed) records: ' . $totalImported;
        $stats[] = 'Total skipped records: ' . $totalFailed;

        return $stats;
    }

    public function onLeaderboardSettingsUpdate()
    {
        if (!empty(post('display_year')) && !empty(post('display_month'))) {
            //Update year & month

            $setting_keys = ['display_year', 'display_month'];
            $year_updated = $month_updated = false;

            foreach ($setting_keys as $setting_key) {
                $setting = ResultsWidgetSettings::where('program_id', '=', $this->program->id)
                    ->where('key', '=', 'leaderboard_' . $setting_key)
                    ->first();

                if ($setting) {
                    $setting->value = post($setting_key);
                } else {
                    $setting = new ResultsWidgetSettings();
                    $setting->key = 'leaderboard_' . $setting_key;
                    $setting->program_id = $this->program->id;
                    $setting->value = post($setting_key);
                }

                if ($setting->save()) {
                    if ($setting_key == 'display_year') {
                        $year_updated = true;
                    }
                    if ($setting_key == 'display_month') {
                        $month_updated = true;
                    }
                }
            }

            $settings = ResultsWidgetSettings::where('program_id', '=', $this->program->id)
                ->where('key', '=', 'leaderboard_display_points')
                ->first();

            if ($settings) {
                $settings->value = post('display_points') ?? 'off';
            } else {
                $settings = new ResultsWidgetSettings();
                $settings->key = 'leaderboard_display_points';
                $settings->program_id = $this->program->id;
                $settings->value = post('display_points') ?? 'off';
            }

            $display_points_updated = $settings->save();

            if ($year_updated && $month_updated && $display_points_updated) {
                Flash::success("Settings saved.");
            } else {
                Flash::error("Error while saving settings.");
            }
        }
    }

    public function onExportLeaderboardReport()
    {
        $filename = storage_path('/csv/export/LeaderboardReport.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Business Name',
            'CRM',
            'Total Points'
        ];

        fputcsv($handle, $outputarray);

        $data = $this->getLeaderboardReportData();
        if (!empty($data)) {
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row['business_name'],
                    $row['crm'],
                    $row['score'],
                ]);
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'LeaderboardReport.csv', $headers);
    }

    private function getLeaderboardReportData()
    {
        $programSections = ScorecardSection::where('program_id', '=', $this->program->id)->pluck('id');
        if ($programSections) {
            $programCriteria = ScorecardCriteria::whereIn('scorecard_section_id', $programSections)->pluck('id');
            if ($programCriteria) {
                return $this->getLeaderboardData($programCriteria);
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
}
