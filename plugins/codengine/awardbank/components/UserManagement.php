<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Auth;
use Codengine\Awardbank\Models\Product;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\UserImport;
use Event;
use Config;
use Illuminate\Support\Facades\DB;
use October\Rain\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\AjaxException;
use October\Rain\Exception\ValidationException;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Models\User;
use Response;

class UserManagement extends ComponentBase
{
    private $limitperimport = 200;
    private $user;
    private $program;
    private $manager = false;
    private $navoptions = [];
    private $userlist = [];
    private $orderBy = 'id';
    private $orderType = 'ASC';
    public  $navoption;
    public $html1;
    public $html2;

    private static $userRules = [
        'crm' => ['member_store_required'],
        'first_name' => [],
        'last_name' => [],
        'business_name' => ['required'],
        //'membership_level' => ['store_member_required'],
        'address_1' => ['store_member_required'],
        'address_2' => [],
        'suburb' => ['store_member_required'],
        'state' => ['store_member_required'],
        'postcode' => ['store_member_required'],
        //'phone' => [/*'store_member_required'*/],
        'email' => ['required'],
        'kam' => ['member_store_required'],
        'roll' => ['required'],
        //'retail_program' => [],
        //'ranking_2021' => []
    ];

    private static $importFileColumnsMapping = [
        'CRM' => 'crm', 'First Name' => 'first_name', 'Last Name' => 'last_name',
        'Business Name' => 'business_name', 'Email' => 'email',
        'Address 1' => 'address_1', 'Address 2' => 'address_2',
        'Suburb' => 'suburb', 'Postcode' => 'postcode', 'State' => 'state',
        'KAM' => 'kam', 'User Type' => 'roll'
    ];

    public function componentDetails()
    {
        return [
            'name'        => 'User Management',
            'description' => 'User Management'
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
        }

        $this->setNavOption();
    }

    public function onRun()
    {
        $this->coreLoadSequence();
    }

    public function coreLoadSequence()
    {
        $this->setNavOptions($this->navoption);
        if(in_array($this->navoption,['list', 'catalog'])){
            $this->getUserList();
        }

        $this->generateHtml($this->navoption);
    }

    private function setNavOption()
    {
        if ($this->navoption == null) {
            $this->navoption = post('navoption') ?? ($this->param('navoption') ?? 'list');
        }

        if ($this->navoption == 'default') {
            $this->navoption = $this->manager ? 'list' : 'catalog';
        }
    }

    public function setNavOptions($navoption)
    {
        $this->navoptions = [
            'list' => 'Users',
            'report' => 'Export',
            'import' => 'Import',
            'export' => 'Export'
        ];

        if (array_key_exists($navoption, $this->navoptions)
            || in_array($navoption, [
            'user', 'usernew' ,'useredit', 'catalog', 'catalog-user'
            ])
        ) {
            $this->navoption = $navoption;
        } else {
            $this->navoption = 'list';
        }
    }

    public function generateHtml($navoption)
    {
        $this->page['manager'] = $this->manager;
        //Do not render for the non-admin views
        if (!in_array($navoption, ['catalog', 'catalog-user'])) {
            $this->html1 = $this->renderPartial('@programsettingsnav',
                [
                    'navoption' => 'user-management-' . $this->navoption,
                    'user' => $this->user,
                    'program' => $this->program,
                    'is_user_management_page' => true,
                ]
            );
        }

        if (in_array($navoption,['list', 'catalog'])) {
            $this->html2 = $this->renderPartial('@' . $this->navoption,
                [
                    'users' => $this->userlist,
                    'program' => $this->program,
                    'orderBy' => Session::get('UserManagement::orderBy'),
                    'orderType' => Session::get('UserManagement::orderType'),
                    'catalogview' => in_array($navoption,['catalog', 'catalog-user']),
                ]
            );
        }

        if (in_array($navoption, ['user', 'usernew', 'useredit', 'catalog-user'])) {
            $this->html2 = $this->renderPartial('@' . $this->navoption,
                [
                    'user' => $this->getUser(Request::get('id'))
                ]
            );
        }

        if ($navoption == 'import') {
            $this->html2 = $this->renderPartial('@' . $this->navoption,
                [
                    'user' => $this->getUser(Request::get('id'))
                ]
            );
        }

        if ($navoption == 'export') {
            $this->html2 = $this->renderPartial('@' . $this->navoption,
                [
                    'user' => $this->getUser(Request::get('id')),
                    'default_start_date' => date('Y-m-d', strtotime('- 30 days')),
                    'default_end_date' => date('Y-m-d'),
                ]
            );
        }
    }

    private function getUserList($page = 1, $searchterm = null, $orderBy = null)
    {
        if (!empty($this->program)) {
            $user_ids = DB::table('codengine_awardbank_u_p')
                ->select('user_id')
                ->where('program_id', '=', $this->program->id)
                ->pluck('user_id')
                ->toArray();

            if (!empty($searchterm)) {
                $query = User::whereIn('id', $user_ids)
                    ->where(function ($query) use ($searchterm) {
                      $query->where('full_name', 'like', '%' . $searchterm . '%')
                          ->orWhere('email', 'like', '%' . $searchterm . '%')
                          ->orWhere('business_name', 'like', '%' . $searchterm . '%')
                          ->orWhere('crm', 'like', '%' . $searchterm . '%')
                          ->orWhere('state', 'like', '%' . $searchterm . '%')
                          ->orWhere('kam', 'like', '%' . $searchterm . '%')
                          ->orWhere('roll', 'like', '%' . $searchterm . '%');
                    });
            } else {
                $query = User::whereIn('id', $user_ids);
            }

            //For KAM users filter subordinates only
            if ($this->user->roll == 'KAM') {
                $query->where('kam', '=', $this->user->full_name);
            }

            //For Member users filter colleagues only
            if (in_array(strtolower($this->user->roll), ['member store', 'staff member'])) {
                $query->where('business_name', '=', $this->user->business_name);
            }

            if (!empty($orderBy)) {
                Session::put('UserManagement::orderBy', $orderBy);
                $orderType = Session::get('UserManagement::orderType');
                if ($orderType) {
                    Session::put('UserManagement::orderType', $orderType == 'ASC' ? 'DESC' : 'ASC');
                } else {
                    Session::put('UserManagement::orderType', $this->orderType);
                }

                $query->orderBy($orderBy, Session::get('UserManagement::orderType'));
            } else {
                $orderBySession = Session::get('UserManagement::orderBy');
                if ($orderBySession) {
                    $query->orderBy($orderBySession, Session::get('UserManagement::orderType') ?? $this->orderType);
                } else {
                    $query->orderBy($this->orderBy, $this->orderType);
                }
            }

            $this->userlist = $query->paginate(25, $page);
        }
    }

    private function getUser($id)
    {
        return DB::table('users')->where('id', '=', $id)->first();
    }

    public function onUsersPaginate()
    {
        $this->getUserList(post('page'), post('searchterm'), post('orderBy'));
        $html = $this->renderPartial('@userlist.htm',
            [
                'users' => $this->userlist,
                'orderBy' => Session::get('UserManagement::orderBy'),
                'orderType' => Session::get('UserManagement::orderType'),
            ]
        );

        $result['html']['#users'] = $html;
        return $result;
    }

    public function onUserCreate()
    {
        if ($this->manager) {
            try {
                $errors = $this->validateInputs(post(), 'create');
                if (empty($errors)) {
                    $userImport = new UserImport();
                    $userRecord = $this->createNewUserRecord(post());
                    $userImport->importData([$userRecord]);
                    $user = User::findByEmail($userRecord['email']);
                    if ($user) {
                        $user->crm = Input::get('crm');
                        $user->name = Input::get('name');
                        $user->email = Input::get('email');
                        $user->phone_number = Input::get('phone');
                        $user->surname = Input::get('surname');
                        $user->full_name = Input::get('full_name');
                        $user->business_name = Input::get('business_name');
                        $user->membership_level = Input::get('membership_level');
                        $user->retail_program = Input::get('retail_program');
                        $user->ranking_2021 = Input::get('ranking_2021');
                        $user->address_1 = Input::get('address_1');
                        $user->address_2 = Input::get('address_2');
                        $user->suburb = Input::get('suburb');
                        $user->postcode = Input::get('postcode');
                        $user->state = Input::get('state');
                        $user->kam = Input::get('kam');
                        $user->roll = Input::get('roll');
                        $user->save();

                        //Flash::success('User has been successfully created.');
                        return Redirect::to('/user-management/user?id=' . $user->id);
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

    private function createNewUserRecord($data)
    {
        $record = [];
        $columnsToMap = [
            "crm", "name", "surname", "full_name", "business_name", "membership_level", "address_1" ,"address_2",
            "suburb", "state", "postcode", "phone", "email", "kam", "roll", "retail_program", "ranking_2021"
        ];

        foreach($columnsToMap as $key) {
            if (isset($data[$key])) {
                $record[$key] = $data[$key];
            }
        }

        //Inject current program
        $record['current_program_id'] = $this->program->id;
        $record['programs'] = $this->program->id;
        $record['teams'] = $this->getCurrentProgramTeam()->id;
        if (in_array($record['roll'],['Admin'])) {
            $record['programs_manager'] = $this->program->id;
        }

        return $record;
    }

    public function onUserUpdate()
    {
        if ($this->manager) {
            try {
                $errors = $this->validateInputs(post(), 'edit');
                if (empty($errors)) {
                    if ($this->manager) {
                        $user = User::find(Input::get('id'));
                        $user->crm = Input::get('crm');
                        $user->name = Input::get('name');
                        $user->phone_number = Input::get('phone');
                        $user->surname = Input::get('surname');
                        $user->full_name = Input::get('full_name');
                        $user->business_name = Input::get('business_name');
                        $user->membership_level = Input::get('membership_level');
                        $user->retail_program = Input::get('retail_program');
                        $user->ranking_2021 = Input::get('ranking_2021');
                        $user->address_1 = Input::get('address_1');
                        $user->address_2 = Input::get('address_2');
                        $user->suburb = Input::get('suburb');
                        $user->postcode = Input::get('postcode');
                        $user->state = Input::get('state');
                        $user->kam = Input::get('kam');
                        $user->roll = Input::get('roll');
                        $user->save();
                    }

                    Flash::success("User updated.");
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

    private function validateInputs($data, $action) {
        //If new user check if email is unique
        $errors = [];
        if ($action == 'create') {
            $userExists = User::findByEmail($data['email']);
            if ($userExists) {
                $errors[] = 'Provided email address is already in use. Please find & edit the other user or provide a different email address.';
            }

            $validator = Validator::make(
                ['email' => $data['email']],
                ['email' => ['required', 'email', 'min:5']]
            );

            if ($validator->fails()) {
                foreach($validator->messages()->all() as $message) {
                    $errors[] = $message;
                }
            }
        }

        $userRules = self::$userRules;
        if ($action == 'edit') {
            $userRules['email'] = [];
        }
        $columnLabels = array_flip(self::$importFileColumnsMapping);

        foreach ($userRules as $required_column => $rules) {
            foreach ($rules as $rule) {
                switch ($rule) {
                    case 'required' :
                        {
                            if (empty($data[$required_column])) {
                                $errors[] = $columnLabels[$required_column] . ' is required.';
                            }
                        }
                        break;
                    case 'store_member_required' :
                    {
                        if (in_array(strtolower($data['roll']), ['member store', 'staff member'])
                            && empty($data[$required_column])
                        ) {
                            $errors[] = $columnLabels[$required_column] . ' is required.';
                        }
                        break;
                    }
                    case 'member_store_required' :
                    {
                        if (in_array(strtolower($data['roll']), ['member store'])
                            && empty($data[$required_column])
                        ) {
                            $errors[] = $columnLabels[$required_column] . ' is required.';
                        }
                        break;
                    }
                }
            }
        }

        return $errors;
    }

    public function onUserActivate()
    {
        if ($this->manager) {
            try {
                if (!empty(post('id'))) {
                    $user = User::withTrashed()->find(post('id'));
                    $user->restore();
                }

                $result['html']['#user-controls-' . post('id')] = $this->renderPartial('@usercontrols', [
                    'user' => $user,
                    'manager' => true,
                ]);
                $result['updatesucess'] = "User activated.";
                return $result;
            } catch (\Exception $ex) {
                $result['manualerror'] = $ex->getMessage();
                return $result;
            }
        }
    }

    public function onUserDeactivate()
    {
        if ($this->manager) {
            try {
                if (!empty(post('id'))) {
                    $user = User::find(post('id'));
                    $user->delete();
                }

                $result['html']['#user-controls-' . post('id')] = $this->renderPartial('@usercontrols', [
                    'user' => $user,
                    'manager' => true,
                ]);
                $result['updatesucess'] = "User suspended.";
                return $result;
            } catch (\Exception $ex) {
                $result['manualerror'] = $ex->getMessage();
                return $result;
            }
        }
    }

    public function onImport()
    {
        if ($this->manager) {
            if (Input::has('file')) {
                $path = Input::file('file')->getRealPath();
                $records = $this->parseCsvFile($path);
                $errors = $this->validateUserRecords($records);
                //If there are no file errors AND
                if (empty($errors['file_errors']) && (empty($errors['data_errors']) || Input::has('skip_errors'))) {
                    $this->page['import_stats'] = $this->importRecords($records, $errors);
                } else {
                    $this->page['import_errors'] = $errors;
                }
            } else {
                $errors['file_errors'][] = 'Invalid source file';
                $this->page['import_errors'] = $errors;
            }
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

    private function validateUserRecords($records)
    {
        $errors = [
            'file_errors' => [],
            'data_errors' => []
        ];

        if (empty($records)) {
            $errors['file_errors'][] = 'No valid records detected.';
            return $errors;
        }

        if (count($records) > $this->limitperimport) {
            $errors['file_errors'][] = 'Your source file contains more then ' . $this->limitperimport . ' records (' . count($records) . '). Please split the records into multiple files and try again.';
        }

        //Check columns - All records have the same columns so we only need to check one
        $flippedImportFileColumnsMapping = array_flip(self::$importFileColumnsMapping);
        $last_record = $records[count($records) - 1];
        if (is_array($last_record)) {
            $last_record_columns = array_keys($last_record);
            foreach (self::$userRules as $required_column => $rules) {
                if (!in_array($flippedImportFileColumnsMapping[$required_column], $last_record_columns)) {
                    $errors['file_errors'][] = 'Column '
                        . $flippedImportFileColumnsMapping[$required_column]
                        . ' is missing. Please add it to your source file or check the current file for misspelling.';
                }
            }

            //Check each record attributes
            $index = 1;
            foreach ($records as $key => $record) {
                $errors['data_errors'][$key] = [];
                foreach ($record as $attr => $value) {
                    //Skip unrecognized columns
                    if (!isset(self::$importFileColumnsMapping[$attr])) {
                       continue;
                    }

                    if (!empty(self::$userRules[self::$importFileColumnsMapping[$attr]])) {
                        foreach (self::$userRules[self::$importFileColumnsMapping[$attr]] as $rule) {
                            switch ($rule) {
                                case 'required' :
                                {
                                    if (empty($value)) {
                                        $errors['data_errors'][$key][] = 'Record ' . $index . ' has invalid ' . $attr . ' value.';
                                    }
                                } break;
                                case 'store_member_required' : {
                                    if (isset($record['User Type'])) {
                                        if (in_array(strtolower($record['User Type']), ['member store', 'staff member'])
                                            && empty($value)) {
                                            $errors['data_errors'][$key][] = 'Record ' . $index . ' has invalid ' . $attr . ' value.';
                                        }
                                    }
                                } break;
                                case 'member_store_required' :
                                {
                                    if (isset($record['User Type'])) {
                                        if (in_array(strtolower($record['User Type']), ['member store'])
                                            && empty($value)) {
                                            $errors['data_errors'][$key][] = 'Record ' . $index . ' has invalid ' . $attr . ' value.';
                                        }
                                    }
                                } break;
                                /*case 'not_store_member_required' : {
                                    if ((stripos($record['User Type'], 'member') !== false) && empty($value)) {
                                        $errors['data_errors'][$key][] = 'Record ' . $index . ' has invalid ' . $attr . ' value.';
                                    }
                                }*/
                            }
                        }
                    }
                }
                if (empty($errors['data_errors'][$key])) {
                    unset($errors['data_errors'][$key]);
                }
                $index++;
            }
        } else {
            $errors['file_errors'][] = 'No valid records detected.';
        }

        return $errors;
    }

    private function importRecords($records, $errors)
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
                $userImport = new UserImport();
                $userRecord = $this->sanitizeUserRecord($record);
                $userImport->importData([$userRecord]);
                $user = User::findByEmail($userRecord['email']);
                if ($user) {
                    //Note: Be Aware of "'model.beforeSave'" plugins/codengine/awardbank/Plugin.php
                    $user->name = $userRecord['name'];
                    $user->surname = $userRecord['surname'];
                    $user->full_name = $userRecord['full_name'];
                    $user->membership_level = $userRecord['membership_level'];
                    $user->retail_program = $userRecord['retail_program'];
                    $user->ranking_2021 = $userRecord['ranking_2021'];
                    $user->phone_number = $userRecord['phone_number'];
                    $user->address_1 = $userRecord['address_1'];
                    $user->address_2 = $userRecord['address_2'];
                    $user->suburb = $userRecord['suburb'] ?? '';
                    $user->postcode = $userRecord['postcode'] ?? '';
                    $user->state = $userRecord['state'] ?? '';
                    $user->crm = $userRecord['crm'] ?? '';
                    $user->kam = $userRecord['kam'] ?? '';
                    $user->roll = $userRecord['roll'];
                    $user->save();

                    $totalImported++;
                }
            }
        }

        $stats[] = 'Total successfully imported (processed) users: ' . $totalImported;
        $stats[] = 'Total skipped records: ' . $totalFailed;

        return $stats;
    }

    private function sanitizeUserRecord($record)
    {
        $userRecord = [];
        $userRecord['full_name'] = trim(implode(' ', [$record['First Name'], $record['Last Name']]));
        $userRecord['name'] = $record['First Name'] ?? '';
        $userRecord['surname'] = $record['Last Name'] ?? '';
        $userRecord['email'] = $record['Email'];
        $userRecord['phone_number'] = $record['Phone'] ?? '';
        $userRecord['username'] = $record['Username'] ?? ($userRecord['email'] ?? '');
        $userRecord['business_name'] = $record['Business Name'];
        $userRecord['membership_level'] = $record['Membership Level'] ?? '';
        $userRecord['retail_program'] = $record['Retail Program'] ?? '';
        $userRecord['ranking_2021'] = $record['2021 Ranking'] ?? '';
        $userRecord['address_1'] = $record['Address 1'];
        $userRecord['address_2'] = $record['Address 2'];
        $userRecord['suburb'] = $record['Suburb'];
        $userRecord['state'] = $record['State'];
        $userRecord['postcode'] = $record['Postcode'];
        $userRecord['phone'] = $record['Phone'] ?? '';
        $userRecord['crm'] = $record['CRM'] ?? null;
        $userRecord['kam'] = $record['KAM'];
        $userRecord['roll'] = $record['User Type'];

        //Inject current program
        $userRecord['current_program_id'] = $this->program->id;
        $userRecord['programs'] = $this->program->id;
        $userRecord['teams'] = $this->getCurrentProgramTeam()->id;
        if (in_array($record['User Type'],['Admin'])) {
            $userRecord['programs_manager'] = $this->program->id;
        }
        /*$userRecord['teams_manager'] = '';*/

        return $userRecord;
    }

    private function getCurrentProgramTeam()
    {
        $team = Team::where('program_id', '=', $this->program->id)->first();
        if (empty($team)) {
            $team = new Team([
                'name' => $this->program->name . ' Team',
                'program_id' => $this->program->id,
                'primary_color' => $this->program->primary_color,
                'secondary_color' => $this->program->secondary_color
            ]);

            $team->save();
        }

        return $team;
    }

    public function onExportUsers()
    {
        if ($this->manager) {
            try {
                if (Input::has('created_at_start') && Input::has('created_at_end')) {
                    if (empty(Input::get('created_at_start')) || empty(Input::get('created_at_end'))) {
                        $result['manualerror'] = 'Invalid date range. Please specify start & end dates.';
                        return $result;
                    } else {
                        return $this->generateUserCsvReport(
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

    private function generateUserCsvReport($program_id, $start_date, $end_date)
    {
        $filename = storage_path('/csv/export/UserExport.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'CRM',
            'First Name',
            'Last Name',
            'Email',
            'Business Name',
            'Address 1',
            'Address 2',
            'Suburb',
            'Postcode',
            'State',
            'KAM',
            'User Type',
            'Last Login',
            'Last Seen'
        ];

        fputcsv($handle, $outputarray);

        $user_ids = DB::table('codengine_awardbank_u_p')
            ->select('user_id')
            ->where('program_id', '=', $program_id)
            ->pluck('user_id')
            ->toArray();

        $users_data = User::whereIn('id', $user_ids)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();

        if (!empty($users_data)) {
            foreach ($users_data as $row) {
                fputcsv($handle, [
                    $row->crm,
                    $row->name,
                    $row->surname,
                    $row->email,
                    $row->business_name,
                    $row->address_1,
                    $row->address_2,
                    $row->suburb,
                    $row->postcode,
                    $row->state,
                    $row->kam,
                    $row->roll,
                    !is_null($row->last_login) ? date('d/m/y h:i a', strtotime((string)$row->last_login)) : '',
                    !is_null($row->last_seen) ? date('d/m/y h:i a', strtotime((string)$row->last_seen)) : ''
                ]);
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download($filename, 'UserExport.csv', $headers);
    }

    public function onExportUsersWishlist()
    {
        if ($this->manager) {
            try {
                return $this->generateUserWishlistCsvReport($this->program->id);
            } catch (\Exception $ex) {
                $result['manualerror'] = $ex->getMessage();
                return $result;
            }
        }
    }

    private function generateUserWishlistCsvReport($program_id)
    {
        $filename = storage_path('/csv/export/UserWishlistExport.csv');
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'CRM',
            'Full Name',
            'Business Name',
            'Product Id',
            'Product Name',
            'Product Quantity'
        ];

        fputcsv($handle, $outputarray);

        $user_ids = DB::table('codengine_awardbank_u_p')
            ->select('user_id')
            ->where('program_id', '=', $program_id)
            ->pluck('user_id')
            ->toArray();

        if (!empty($user_ids)) {
            $users_records = User::whereIn('id', $user_ids)
                ->select('id', 'crm', 'full_name', 'business_name', 'wishlist_array')
                ->whereRaw('LENGTH(crm)>0')
                ->whereNotNull('wishlist_array')
                ->get();

            $productsNames = Product::withTrashed()
                ->get()
                ->pluck('name', 'id');

            if (!empty($users_records)) {
                $users_data = [];
                foreach ($users_records as $users_record) {
                    $users_data[$users_record->id] = [
                        'crm' => $users_record->crm,
                        'full_name' => $users_record->full_name,
                        'business_name' => $users_record->business_name,
                        'wishlist_array' => $this->getUserProgramWishlistItems(
                            $program_id,
                            $productsNames,
                            $users_record->wishlist_array
                        ),
                    ];
                }

                if (!empty($users_data)) {
                    foreach ($users_data as $row) {
                        if (!empty($row['wishlist_array'])) {
                            foreach ($row['wishlist_array'] as $wishlistItem) {
                                fputcsv($handle, [
                                    $row['crm'],
                                    $row['full_name'],
                                    $row['business_name'],
                                    $wishlistItem['id'],
                                    $wishlistItem['name'],
                                    $wishlistItem['volume'],
                                ]);
                            }
                        }
                    }
                }
            }
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download($filename, 'UserWishlistExport.csv', $headers);
    }

    private function getUserProgramWishlistItems($program_id, $productsNames, $wishlist_array) {

        $wishlist = [];
        if (!empty($wishlist_array)) {
            foreach ($wishlist_array as $program_key => $program_items) {
                if ($program_id == $program_key) {
                    foreach ($program_items as $item) {
                        $wishlist[] = [
                            'id' => $item['id'],
                            'name' => $productsNames[$item['id']] ?? 'Unknown',
                            'volume' => $item['volume']
                        ];
                    }
                }
            }
        }

        return $wishlist;
    }
}
