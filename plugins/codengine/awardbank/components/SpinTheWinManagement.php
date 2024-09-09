<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\SpinTheWinPrice;
use Codengine\Awardbank\Models\SpinTheWinResult;
use Codengine\Awardbank\Models\SpinTheWinSettings;
use October\Rain\Support\Facades\Input;
use October\Rain\Exception\AjaxException;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use Response;
use stdClass;

class SpinTheWinManagement extends ComponentBase
{
    private $user;
    private $program;
    private $manager = false;
    private $price;

    public $navoption;
    public $html1;
    public $html2;
    public $priceLogoComponent;

    public function componentDetails()
    {
        return [
            'name' => 'Spin The Win Settings',
            'description' => 'Spin The Win Settings',
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
            if (!empty(post('id')) && !empty('_uploader')) {
                $this->price = SpinTheWinPrice::find(post('id'));
            } else {
                $this->price = SpinTheWinPrice::first();
            }
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
            $this->navoption = post('navoption') ?? ($this->param('navoption') ?? 'settings');
        }

        if ($this->navoption == 'default') {
            $this->navoption = 'settings';
        }
    }

    public function generateHtml()
    {
        $this->page['manager'] = $this->manager;
        $this->html1 = $this->renderPartial('@programsettingsnav',
            [
                'navoption' => 'spin-the-win-management-' . $this->navoption,
                'user' => $this->user,
                'program' => $this->program,
                'is_spin_the_win_management_page' => true,
            ]
        );

        $this->html2 = $this->renderPartial('@' . $this->navoption,
            $this->getDataByNavOption()
        );
    }

    private function getSettings()
    {
        $settingRecords = SpinTheWinSettings::where('program_id', '=' , $this->program->id)
            ->whereIn('key', ['enabled', 'start_date', 'end_date'])
            ->get();

        $settings = [];

        foreach ($settingRecords as $parameter) {
            $settings[$parameter->key] = $parameter->value;
        }

        return $settings;
    }

    private function getDataByNavOption()
    {
        if ($this->navoption == 'settings') {
            $prices = SpinTheWinPrice::where('program_id','=',$this->program->id)
                ->get();

            $settings = $this->getSettings();

            return [
                'prices' => $prices,
                'enabled' => $settings['enabled'] ?? 0,
                'default_start_date' => $settings['start_date'] ?? date('Y-m-d', strtotime('- 30 days')),
                'default_end_date' => $settings['end_date'] ?? date('Y-m-d'),
            ];
        }

        if ($this->navoption == 'reports') {
            return [
                'default_start_date' => date('Y-m-d', strtotime('- 30 days')),
                'default_end_date' => date('Y-m-d'),
            ];
        }

        if ($this->navoption == 'userdetails') {
            $data = SpinTheWinSettings::where('key', '=', 'userdetails')
                ->where('program_id', '=', $this->program->id)
                ->get();

            $data = json_decode($data, true);

            return [
                'data' => $data
            ];
        }
    }

    public function onSettingsUpdate()
    {
        if ($this->manager) {
            try {
                $errors = [];
                $required_fields = ['start_date' => 'Start Date', 'end_date' => 'End Date'];
                foreach ($required_fields as $key => $label) {
                    if (empty(post($key))) {
                        $errors[] = $label . " is required";
                    }
                }

                if (empty($errors)) {
                    if (strtotime(post('start_date')) > strtotime(post('end_date'))) {
                        Flash::error("Start Date needs to be ahead of the End Date.");
                    } else {
                        $enabled = SpinTheWinSettings::where('program_id', '=', $this->program->id)
                            ->where('key', '=', 'enabled')
                            ->first();

                        if (!$enabled) {
                            try {
                                $enabled = new SpinTheWinSettings();
                                $enabled->program_id = $this->program->id;
                                $enabled->key = 'enabled';
                                $enabled->value = post('enabled') == 'on' ? 1 : 0;
                                $enabled->save();
                            } catch (\Exception $e) {
                                $errors[] = $e->getMessage();
                                Flash::error($e->getMessage());
                            }
                        } else {
                            $enabled->value = post('enabled') == 'on' ? 1 : 0;
                            $enabled->save();
                        }

                        $dates = ['start_date', 'end_date'];
                        foreach ($dates as $date) {
                            $dateSetting = SpinTheWinSettings::where('program_id', '=', $this->program->id)
                                ->where('key', '=', $date)
                                ->first();

                            if (!$dateSetting) {
                                $dateSetting = new SpinTheWinSettings();
                                $dateSetting->program_id = $this->program->id;
                                $dateSetting->key = $date;
                                $dateSetting->value = post($date);
                                $dateSetting->save();
                            } else {
                                $dateSetting->value = post($date);
                                $dateSetting->save();
                            }
                        }

                        if (empty($errors)) {
                            Flash::success("Settings updated.");
                        }
                    }
                } else {
                    Flash::error($errors[0]);
                }
            } catch (\Exception $ex) {
                throw new AjaxException(
                    [
                        'error' => $ex->getMessage(),
                    ]
                );
            }
        }
    }

    public function onNewPrice() {
        if ($this->manager) {
            $errors = [];

            try {
                $price = new SpinTheWinPrice();
                $price->program_id = $this->program->id;
                $price->label = strip_tags(post('label'));
                $price->name = strip_tags(post('name'));
                $price->message = strip_tags(post('message'));
                $price->is_price = intval(post('is_price'));
                $price->quantity = intval(post('quantity'));
                $price->probability = floatval(post('probability'));
                $price->priority = intval(post('priority'));
                $price->save();
            } catch (\Exception $e) {
                //throw new \Exception($e->getMessage());
                $errors[] = $e->getMessage();
            }

            if (empty($errors)) {
                $result['updatesucess'] = 'Prize has been successfully created.';
                return $this->renderPricesPartial($result);
            } else {
                $result['manualerror'] = $errors[0];
                return $result;
            }
        }
    }

    public function onEditPrice()
    {
        if (!empty(post('id'))) {
            $price = SpinTheWinPrice::find(post('id'));
            if ($price) {
                $this->price = $price;
                $result['html']['#price-list-wrapper'] = $this->renderPartial('@priceform', [
                    'price' => $price
                ]);
                return $result;
            }
        }
    }

    public function onDeletePrice()
    {
        if (!empty(post('id'))) {
            $price = SpinTheWinPrice::find(post('id'));
            if ($price->delete()) {
                $result['updatesucess'] = 'Prize has been successfully deleted.';
                return $this->renderPricesPartial($result);
            } else {
                $result['manualerror'] = 'Error while deleting price';
                return $result;
            }
        } else {
            $result['manualerror'] = 'Invalid price provided.';
            return $result;
        }
    }

    public function onUpdatePrice()
    {
        try {
            $price = SpinTheWinPrice::find(post('id'));
            if ($price) {
                $price->label = strip_tags(post('label'));
                $price->name = strip_tags(post('name'));
                $price->message = strip_tags(post('message'));
                $price->is_price = intval(post('is_price'));
                $price->quantity = intval(post('quantity'));
                $price->probability = floatval(post('probability'));
                $price->priority = intval(post('priority'));
                if ($price->save()) {
                    $result['updatesucess'] = 'Prize has been successfully updated.';
                    return $this->renderPricesPartial($result);
                } else {
                    $result['manualerror'] = 'Error while updating price';
                    return $result;
                }
            } else {
                $result['manualerror'] = 'Invalid price provided.';
                return $result;
            }
        } catch (\Exception $ex) {
            $result['manualerror'] = $ex->getMessage();
            return $result;
        }
    }

    private function renderPricesPartial($result = [])
    {
        $prices = SpinTheWinPrice::where('program_id','=',$this->program->id)
            ->get();

        $result['html']['#price-list-wrapper'] = $this->renderPartial('@prices', [
            'prices' => $prices,
        ]);

        return $result;
    }

    private function bindLogoComponent()
    {
        if ($this->price) {
            $this->priceLogoComponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'priceLogo',
                [
                    'deferredBinding' => false
                ]
            );

            $this->priceLogoComponent->bindModel('logo', $this->price);
        }
    }

    public function onExportResults()
    {
        if ($this->manager) {
            try {
                if (Input::has('created_at_start') && Input::has('created_at_end')) {
                    if (empty(Input::get('created_at_start')) || empty(Input::get('created_at_end'))) {
                        $result['manualerror'] = 'Invalid date range. Please specify start & end dates.';
                        return $result;
                    } else {
                        return $this->generateResultsCsvReport(
                            $this->program->id,
                            Input::get('created_at_start'),
                            Input::get('created_at_end')
                        );
                    }
                }
            } catch (\Exception $ex) {
                $result['manualerror'] = $ex->getMessage();
                return $result;
            }
        }
    }

    private function generateResultsCsvReport($program_id, $start_date, $end_date)
    {
        $start_date = date('Y-m-d 00:00:00', strtotime($start_date));
        $end_date = date('Y-m-d 23:59:59', strtotime($end_date));

        $filename = storage_path('/csv/export/SpinToWinResults.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'CRM',
            'Business Name',
            'Email',
            'Prize',
            'Date'
        ];

        fputcsv($handle, $outputarray);

        $results = SpinTheWinResult::join(
            'codengine_awardbank_spin_the_win_prices',
            'codengine_awardbank_spin_the_win_prices.id',
            '=',
            'codengine_awardbank_spin_the_win_results.price_id'
        )->join(
            'users',
            'users.id',
            '=',
            'codengine_awardbank_spin_the_win_results.user_id'
        )->select('users.business_name', 'users.email', 'users.crm',
            'codengine_awardbank_spin_the_win_prices.name', 'codengine_awardbank_spin_the_win_results.created_at')
            ->where('codengine_awardbank_spin_the_win_prices.program_id', '=', $program_id)
            ->whereBetween('codengine_awardbank_spin_the_win_results.created_at', [$start_date, $end_date])
            ->get();

        if (!empty($results)) {

            $timezone = new \DateTimeZone('Australia/Sydney');
            foreach ($results as $row) {
                $created_at = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    (string)$row->created_at
                )->setTimezone($timezone)->format('d/m/y h:i a');

                fputcsv($handle, [
                    $row->crm,
                    $row->business_name,
                    $row->email,
                    $row->name, //created_at
                    $created_at
                ]);
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download($filename, 'SpinTheWinResultsReport.csv', $headers);
    }

    public function onUserDetailsImport()
    {
        if (Input::has('file')) {
            $path = Input::file('file')->getRealPath();
            $records = $this->parseCsvFile($path);

            $errors = [
                'file_errors' => [],
                'data_errors' => []
            ];

            if (empty($records)) {
                $errors['file_errors'][] = 'No valid records detected.';
            }

            if ((!in_array('CRM', array_keys($records[0])) && !in_array('UserId', array_keys($records[0])))
                || !in_array('Spins', array_keys($records[0]))) {
                $errors['file_errors'][] = 'Your source file needs to have these columns: UserId, Spins';
            }

            $columnsToCheck = [
                'UserId' => 'UserId',
                'Spins' => 'Spins'
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

            $userIdentifier = Input::has('user_identifier') ? Input::get('user_identifier') : 'UserId';

            //If there are no file errors AND
            if (empty($errors['file_errors']) && (empty($errors['data_errors']) || Input::has('skip_errors'))) {
                $month = date('Y') . '-' . sprintf('%02d', (int)post('month')) . '-01';
                $this->page['import_stats'] = $this->importUserDetailsRecords($records, $userIdentifier, $errors);
            } else {
                $this->page['import_errors'] = $errors;
            }
        } else {
            $errors['file_errors'][] = 'Invalid source file';
            $this->page['import_errors'] = $errors;
        }
    }

    private function parseCsvFile($filepath)
    {
        $records = array_map('str_getcsv', file($filepath));
        array_walk($records, function(&$a) use ($records) {
            $a = array_combine($records[0], $a);
        });
        array_shift($records); # remove column header
        //$records = array_slice($records, 0, 1);
        return $records;
    }

    private function importUserDetailsRecords($records, $userIdentifier, $errors)
    {
        $data = $stats = [];
        $totalFailed = $totalImported = 0;

        $dataRecord = SpinTheWinSettings::where('key', '=', 'userdetails')
            ->where('program_id', '=', $this->program->id)
            ->first();

        if (!$dataRecord) {
            $dataRecord = new SpinTheWinSettings();
            $dataRecord->key = 'userdetails';
            $dataRecord->value = json_encode([]);
            $dataRecord->program_id = $this->program->id;
            $dataRecord->save();
        }

        $dbRecords = json_decode($dataRecord->value, true);
        //Convert CRM to userID
        if ($userIdentifier == 'CRM') {
            $crmIds = User::whereNotNull('CRM')->where('CRM', '<>', '')->select('id', 'crm')->pluck('id', 'crm')->toArray();
        }

        foreach ($records as $key => $record) {
            //Skip record if it has errors
            if (isset($errors['data_errors'][$key])) {
                $stats[] = implode(', ', $errors['data_errors'][$key]) . ' Record has not been imported.';
                $totalFailed++;
                continue;
            } else {
                if ($userIdentifier == 'CRM') {
                    if (!empty($crmIds) && isset($crmIds[$record['UserId']])) {
                        $dbRecords[$crmIds[$record['UserId']]] = $record['Spins'];
                        $totalImported++;
                    } else {
                        if (!isset($errors['data_errors'][$key])) {
                            $errors['data_errors'][$key][] = 'Record ' . ($key + 1) .' has invalid CRM.';
                        }
                        $stats[] = implode(', ', $errors['data_errors'][$key]) . ' Record has not been imported';
                        $totalFailed++;
                        continue;
                    }
                } else {
                    $dbRecords[$record['UserId']] = $record['Spins'];
                    $totalImported++;
                }
            }
        }

        try {
            $dataRecord->value = json_encode($dbRecords);
            if (!$dataRecord->save()) {
                $totalImported = 0;
                $totalFailed = count($records);
            }
        } catch (\Exception $e) {

        }

        $stats[] = 'Total successfully imported (processed) records: ' . $totalImported;
        $stats[] = 'Total skipped records: ' . $totalFailed;

        return $stats;
    }

}
