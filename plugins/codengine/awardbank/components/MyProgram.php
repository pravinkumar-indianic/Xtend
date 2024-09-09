<?php namespace Codengine\Awardbank\Components;

use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\Models\Address;
use Codengine\Awardbank\Models\Award;
use Codengine\Awardbank\Models\PointsLedger;
use Codengine\Awardbank\Models\Organization;
use Codengine\Awardbank\Models\BillingContact;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Transaction;
use Codengine\Awardbank\Models\Nomination;
use Codengine\Awardbank\Models\Prize;
use Codengine\Awardbank\Models\Thankyou;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Message;
use Codengine\Awardbank\Models\Program;
use Auth;
use Event;
use Carbon\Carbon;
use Redirect;
use Config;
use Mail;
use Response;

class MyProgram extends ComponentBase
{
    /** MODELS **/

    private $user;
    private $config;
    private $users;
    private $program;
    private $imagecomponent;
    private $imagecomponent2;
    private $imagecomponent3;
    private $imagecomponent4;
    private $imagecomponent5;
    private $moduleEnabled;
    private $navoptions = [];
    public  $navoption;
    private $teams = [];
    private $pointsledgers = [];
    private $billingcontact;
    private $pointsaddition = 0;
    private $pointssubtraction = 0;
    private $pointsreturned = 0;
    private $startingpoints = 0;
    private $incart = 0;
    private $availablepoints = 0;
    private $orders;
    private $orderstotal = 0;
    private $value = 1000;
    private $dollarvalue;
    private $limit = 0;
    private $prizes;
    private $filters = [];
    private $current_points = 0;
    private $totalusers = 0;
    private $loggedinusers = 0;
    private $userspoints = 0;
    private $filterresults = [
        'created_at_start' => null,
        'created_at_end' => null,
        'last_seen_start' => null,
        'last_seen_end' => null,
        'order_by' => null,
    ];
    public $html1;
    public $html2;

    public function componentDetails()
    {
        return [
            'name'        => 'My Profile',
            'description' => 'My Profile'
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
            $this->program = $this->user->currentProgram;
            $this->config = Config::get('codengine.awardbank::payway');
            $this->imagecomponent = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'featureImage',
                [
                    'deferredBinding' => false
                ]
            );
            $this->imagecomponent->bindModel('feature_image', $this->program);

            $this->imagecomponent2 = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'featureImageMobile',
                [
                    'deferredBinding' => false
                ]
            );
            $this->imagecomponent2->bindModel('feature_image_mobile', $this->program);

            $this->imagecomponent3 = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'headerIcon',
                [
                    'deferredBinding' => false
                ]
            );
            $this->imagecomponent3->bindModel('header_icon', $this->program);

            $this->imagecomponent4 = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'loginImage',
                [
                    'deferredBinding' => false
                ]
            );
            $this->imagecomponent4->bindModel('login_image', $this->program);

            $this->imagecomponent5 = $this->addComponent(
                'Responsiv\Uploader\Components\FileUploader',
                'sliderImages',
                [
                    'deferredBinding' => false
                ]
            );
            $this->imagecomponent5->bindModel('slider_images', $this->program);
        }
    }

    public function onRun(){
        $this->addJs('/plugins/codengine/awardbank/assets/js/calendar.min.js');
        $this->addJs('https://api.payway.com.au/rest/v1/payway.js');
        $this->addJs('/plugins/codengine/awardbank/assets/js/PayWay.js');
        if ($this->property('mode') != 'program-settings') {
            $this->addJs('/plugins/codengine/awardbank/assets/js/MyProgram231219.js');
        }
        $this->addJs('https://api.payway.com.au/rest/v1/payway.js');
        $this->config = Config::get('codengine.awardbank::payway');
        if($this->navoption == null){
            $this->navoption = $this->param('navoption');
        }
        $this->imagecomponent->fileList = $this->program->featureImage;
        $this->imagecomponent->singleFile = $this->program->featureImage;
        $this->imagecomponent2->fileList = $this->program->feature_image_mobile;
        $this->imagecomponent2->singleFile = $this->program->feature_image_mobile;
        $this->imagecomponent3->fileList = $this->program->header_icon;
        $this->imagecomponent3->singleFile = $this->program->header_icon;
        $this->imagecomponent4->fileList = $this->program->login_image;
        $this->imagecomponent4->singleFile = $this->program->login_image;
        $this->imagecomponent5->fileList = $this->program->slider_images;
        $this->imagecomponent5->singleFile = $this->program->slider_images;
        $this->coreLoadSequence();
    }

    /**
        Component Custom Functions
    **/

    /**
        Reusable function call the core sequence of functions to load and render the Cart partial html
    **/

    public function coreLoadSequence()
    {
        $this->setNavOptions($this->navoption);
        $this->getFilters();
        $this->setFilters();
        if($this->navoption == 'users' || $this->navoption == 'transfers'){
            $users = User::whereHas('programs', function($query){
                $query->where('id','=', $this->user->currentProgram->id);
            });
            if($this->filterresults['created_at_start'] != NULL){
                $users = $users->whereDate('created_at','>',$this->filterresults['created_at_start']);
            }
            if($this->filterresults['created_at_end'] != NULL){
                $users = $users->whereDate('created_at','<',$this->filterresults['created_at_end']);
            }
            if($this->filterresults['last_seen_start'] != NULL){
                $users = $users->whereDate('last_seen','>',$this->filterresults['last_seen_start']);
            }
            if($this->filterresults['last_seen_end'] != NULL){
                $users = $users->whereDate('last_seen','<',$this->filterresults['last_seen_end']);
            }
            $this->users = $users->with('teams')->get();
            if($this->filterresults['order_by'] == 'asc'){
                $this->users = $this->users->sortBy('created_at');
            } else {
                $this->users = $this->users->sortByDesc('created_at');
            }
            $this->getUsers();
        }
        if($this->navoption == 'teams'){
            $this->program = $this->program->load('teams');
            $this->getTeams();
        }
        if($this->navoption == 'transfers'){
            $this->program = $this->program->load('pointsledgers');
            $this->getProgramPointsLedgers();
        }
        if($this->navoption == 'prizes'){
            $this->prizes = Prize::whereHas('award', function($query){
                $query->where('program_id','=', $this->user->currentProgram->id);
            })->get();
            $this->getPrizes();
        }
        if($this->navoption == 'userspoints'){
            $pointsledger = PointsLedger::where('program_id','=',$this->user->currentProgram->id);
            if($this->filterresults['created_at_start'] != NULL){
                $pointsledger = $pointsledger->whereDate('created_at','>',$this->filterresults['created_at_start']);
            }
            if($this->filterresults['created_at_end'] != NULL){
                $pointsledger = $pointsledger->whereDate('created_at','<',$this->filterresults['created_at_end']);
            }
            $this->program->pointsledgers = $pointsledger->where(function($query){
                $query->where('type','!=',5)
                ->where('type','!=',6);
            })->with('user')->orderBy('id','desc')->get();
            if($this->filterresults['order_by'] == 'asc'){
                $this->program->pointsledgers = $this->program->pointsledgers->sortBy('created_at');
            } else {
                $this->program->pointsledgers = $this->program->pointsledgers->sortByDesc('created_at');
            }
            $this->getUsersPointsLedgers();
        }
        if($this->navoption == 'orders'){
            $this->program = $this->program->load('orders');
            $this->getOrders();
        }
        if($this->navoption == 'nominations'){
            $nominations = Nomination::where('program_id','=',$this->user->currentProgram->id);
            if($this->filterresults['created_at_start'] != NULL){
                $nominations = $nominations->whereDate('created_at','>',$this->filterresults['created_at_start']);
            }
            if($this->filterresults['created_at_end'] != NULL){
                $nominations = $nominations->whereDate('created_at','<',$this->filterresults['created_at_end']);
            }

            $this->program->nominations = $nominations->with(['approver', 'award', 'nominator', 'user', 'team', 'nomination_image', 'nomination_file'])->withCount('votes')->when($this->filterresults['order_by'] == 'asc', function ($query) {
                return $query->orderBy('created_at', 'desc');
            }, function ($query) {
                return $query->orderBy('created_at', 'desc');
            })->take(300)->get();

            $this->program->nominations = $this->program->nominations->each(function($nomination){
                $nomination->managed = true;
                return $nomination;
            });
        }
        if($this->navoption == 'thankyous'){
            $thankyous = Thankyou::where('program_id','=',$this->user->currentProgram->id);
            if($this->filterresults['created_at_start'] != NULL){
                $thankyous = $thankyous->whereDate('created_at','>',$this->filterresults['created_at_start']);
            }
            if($this->filterresults['created_at_end'] != NULL){
                $thankyous = $thankyous->whereDate('created_at','<',$this->filterresults['created_at_end']);
            }
            $this->program->thankyous = $thankyous->get();
            if($this->filterresults['order_by'] == 'asc'){
                $this->program->thankyous = $this->program->thankyous->sortBy('created_at');
            } else {
                $this->program->thankyous = $this->program->thankyous->sortByDesc('created_at');
            }
        }
        if($this->navoption == 'billingdetails'){
            $this->program = $this->program->load('billingcontact');
            $this->billingcontact = $this->program->billingcontact;
        }
        if($this->navoption == 'buypoints'){
            $this->program = $this->program->load('billingcontact');
            $this->billingcontact = $this->program->billingcontact;
            $this->dollarvalue = $this->value / $this->program->scale_points_by;
        }
        $this->generateHtml();
    }

    public function setNavOptions($navoption)
    {
        $this->navoptions = [
            'generaldetails' => 'General Details',
            'billingdetails' => 'Billing Details',
            'images' => 'Images',
            'address' => 'Address',
            'organization' => 'Organisation',
            'renewals' => 'Renewal Info',
            'transactions' => 'Transactions',
            'buypoints' => 'Buy '.$this->program->points_name,
            'transfers' => 'Transfer & Program '.$this->program->points_name,
            'prizes' => 'Prizes',
            'userspoints' => 'Users '.$this->program->points_name,
            'users' => 'Users',
            'teams' => $this->program->team_label,
            'orders' => 'Orders',
            'nominations' => 'Nominations',
            'thankyous' => 'Thankyous',
        ];
        if(array_key_exists($navoption, $this->navoptions)){
            $this->navoption = $navoption;
        } else {
            $this->navoption = 'generaldetails';
        }
    }

    public function setFilters()
    {
        if($this->navoption == 'users'){
            $this->filters = [
                'created_at_start' => [
                    'label' => 'Created At Start Range',
                    'name' => 'created_at_start',
                    'type' => 'date',
                    'value' => $this->filterresults['created_at_start'],
                ],
                'created_at_end' => [
                    'label' => 'Created At End Range',
                    'name' => 'created_at_end',
                    'type' => 'date',
                    'value' => $this->filterresults['created_at_end'],
                ],
                'last_seen_start' => [
                    'label' => 'Last Logged In Start Range',
                    'name' => 'last_seen_start',
                    'type' => 'date',
                    'value' => $this->filterresults['last_seen_start'],
                ],
                'last_seen_end' => [
                    'label' => 'Last Logged In End Range',
                    'name' => 'last_seen_end',
                    'type' => 'date',
                    'value' => $this->filterresults['last_seen_end'],
                ],
                'order_by' => [
                    'label' => 'Order By',
                    'name' => 'order_by',
                    'type' => 'dropdown',
                    'options' => [
                        'desc' => 'Created Descending',
                        'asc' => 'Created Ascending',
                    ],
                    'value' => $this->filterresults['order_by'],
                ],
            ];
        } else {
            $this->filters = [
                'created_at_start' => [
                    'label' => 'Created At Start Range',
                    'name' => 'created_at_start',
                    'type' => 'date',
                    'value' => $this->filterresults['created_at_start'],
                ],
                'created_at_end' => [
                    'label' => 'Created At End Range',
                    'name' => 'created_at_end',
                    'type' => 'date',
                    'value' => $this->filterresults['created_at_end'],
                ],
                'order_by' => [
                    'label' => 'Order By',
                    'name' => 'order_by',
                    'type' => 'dropdown',
                    'options' => [
                        'desc' => 'Created Descending',
                        'asc' => 'Created Ascending',
                    ],
                    'value' => $this->filterresults['order_by'],
                ],
            ];
        }
    }

    public function getFilters()
    {
        if($this->testPost(post('created_at_start')) == true){
            $this->filterresults['created_at_start'] = new Carbon(post('created_at_start'));
        } else {
            $this->filterresults['created_at_start'] = null;
        }
        if($this->testPost(post('created_at_end')) == true){
            $this->filterresults['created_at_end'] =  new Carbon(post('created_at_end'));
        } else {
            $this->filterresults['created_at_end'] = null;
        }
        if($this->testPost(post('last_seen_start')) == true){
            $this->filterresults['last_seen_start'] = new Carbon(post('last_seen_start'));
        } else {
            $this->filterresults['last_seen_start'] = null;
        }
        if($this->testPost(post('last_seen_end')) == true){
            $this->filterresults['last_seen_end'] =  new Carbon(post('last_seen_end'));
        } else {
            $this->filterresults['last_seen_end'] = null;
        }
        if($this->testPost(post('order_by')) == true){
            $this->filterresults['order_by'] = post('order_by');
        } else {
            $this->filterresults['order_by'] = null;
        }
    }

    public function getUsers()
    {
        $this->users = $this->users->each(function($user){
            $this->totalusers = $this->totalusers + 1;
            if($user->last_login != NULL){
                $this->loggedinusers = $this->loggedinusers + 1;
            }

            $user->current_points = 0;
            if(isset($user->points_json[$this->user->currentProgram->id])){
                $user->runMyPoints(true);
                //dump($user->points_json[$this->user->currentProgram->id]);
                $user->current_points = $user->points_json[$this->user->currentProgram->id]['points'] + $user->points_json[$this->user->currentProgram->id]['inCart'];
                $this->userspoints = $this->userspoints + $user->current_points;
            }
            $user->managed = true;
            return $user;
        });
    }

    public function getTeams()
    {
        $this->teams = $this->program->teams()->with('parent','children')->orderBy('name','asc')->get();
        $this->teams = $this->teams->each(function($team){
            $team->managed = true;
            return $team;
        });
    }

    public function getProgramPointsLedgers()
    {
        $this->pointsaddition = 0;
        $this->pointssubtraction = 0;
        $this->pointsreturned = 0;
        $this->pointsledgers = $this->program->pointsledgers()->where(function($query){
            $query->whereIn('type',[5,6,7,8]);
        })->with('user')->orderBy('id','desc')->get();
        $this->pointsledgers = $this->pointsledgers->each(function($pointsledger){
            $pointsledger->user = $pointsledger->user()->withTrashed()->first();
            //dump($pointsledger->user()->withTrashed()->first());
            $pointsledger->points_name = $this->user->currentProgram->points_name;
            if($pointsledger->type == 'Program Points Addition'){
                $this->pointsaddition = $this->pointsaddition + $pointsledger->points_value;
            }
            if($pointsledger->type == 'Program Points Transfer'){
                $this->pointssubtraction = $this->pointssubtraction + $pointsledger->points_value;
            }
            if($pointsledger->type == 'Program Points Return'){
                $this->pointsreturned = $this->pointsreturned + $pointsledger->points_value;
            }
            if($pointsledger->type == 'Program Points Subtraction'){
                $this->pointsaddition = $this->pointsaddition - $pointsledger->points_value;
            }
            return $pointsledger;
        });
        $this->availablepoints = $this->pointsaddition - $this->pointssubtraction;
    }

    public function getPrizes()
    {
        $this->prizes = $this->prizes->each(function($prize){
            $prize->points_name = 'Points';
            if($prize->award->program){
                $prize->points_name = $prize->award->program->points_name;
            }
            $prize->color = $prize->award->secondary_color;
            return $prize;
        });
    }

    public function getUsersPointsLedgers()
    {
        $this->startingpoints = 0;
        $this->pointsaddition = 0;
        $this->pointssubtraction = 0;
        $this->incart = 0;

        $this->pointsledgers = $this->program->pointsledgers;
        $this->pointsledgers = $this->pointsledgers->each(function($pointsledger){
            $pointsledger->points_name = $this->user->currentProgram->points_name;
            if($pointsledger->type == null){
                $this->startingpoints = $this->startingpoints + $pointsledger->points_value;
            }
            if($pointsledger->type == 'Addition Value'){
                $this->pointsaddition = $this->pointsaddition + $pointsledger->points_value;
            }
            if($pointsledger->type == 'Subtraction Value'){
                $this->pointsaddition = $this->pointsaddition + $pointsledger->points_value;
            }
            if($pointsledger->type == 'In Cart'){
                $this->incart = $this->incart + $pointsledger->points_value;
            }
            if($pointsledger->type == 'Out Cart'){
                $this->incart = $this->incart - $pointsledger->points_value;
            }
            return $pointsledger;
        });
        $this->availablepoints = ($this->startingpoints + $this->pointsaddition) - ($this->incart + $this->pointssubtraction);
    }

    public function getOrders()
    {
        $this->orders = $this->program->orders()->with('orderplacer')->orderBy('id','desc')->get();
        $this->orders = $this->orders->each(function($order){
            $order->points_name = $this->user->currentProgram->points_name;
            $this->orderstotal = $this->orderstotal + $order->points_value;
            return $order;
        });
    }

    public function getBillingContact()
    {

    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {

        $this->html1 = $this->renderPartial('@programlhsnav',
            [
                'navoptions' => $this->navoptions,
                'navoption' => $this->navoption,
                'program' => $this->program,
            ]
        );

        $this->html2 = $this->renderPartial('@'.$this->navoption,
            [
                'user' => $this->user,
                'users' => $this->users,
                'program' => $this->program,
                'teams' => $this->teams,
                'pointsledgers' => $this->pointsledgers,
                'pointsaddition' => $this->pointsaddition,
                'pointssubtraction' => $this->pointssubtraction,
                'pointsreturned' => $this->pointsreturned,
                'startingpoints' => $this->startingpoints,
                'availablepoints' => $this->availablepoints,
                'totalusers' => $this->totalusers,
                'loggedinusers' => $this->loggedinusers,
                'incart' => $this->incart,
                'orders' => $this->orders,
                'prizes' => $this->prizes,
                'orderstotal' => $this->orderstotal,
                'userspoints' => $this->userspoints,
                'billingcontact' => $this->billingcontact,
                'publishablekey' => $this->config['publishableAPI'],
                'value' => $this->value,
                'dollarvalue' => $this->dollarvalue,
                'limit' => $this->limit,
                'filters' => $this->filters,
                'navoption' => $this->navoption,
            ]
        );
    }

    public function testPost($input)
    {
        if($input != '' && !empty($input) && $input != 'empty'){
            return true;
        } else {
            return false;
        }
    }

    /**
        AJAX Requests
    **/

    public function onUpdateTab()
    {
        if($this->testPost(post('navoption')) == true){
            $this->navoption = post('navoption');
            if($this->navoption  == 'billingdetails'){
                $result['paywayrun'] = 1;
            }
            if($this->navoption  == 'images'){
                $result['fileuploaderrun'] = 1;
            }
        }
        $this->pageCycle();
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        return $result;
    }

    public function onUpdateGeneralDetails()
    {
        try{
            if($this->testPost(post('Name')) == true){
                $this->program->name = post('Name');
            }
            if($this->testPost(post('Subdomain')) == true){
                $this->program->slug = post('Subdomain');
            }
            if($this->testPost(post('Primary_Color')) == true){
                $this->program->primary_color = post('Primary_Color');
            }
            if($this->testPost(post('Secondary_Color')) == true){
                $this->program->secondary_color = post('Secondary_Color');
            }
            if($this->testPost(post('Team_Label')) == true){
                $this->program->team_label = post('Team_Label');
            }
            if($this->testPost(post('Points_Name')) == true){
                $this->program->points_name = post('Points_Name');
            }
            if($this->testPost(post('Scale_Points_By')) == true){
                $this->program->scale_points_by = post('Scale_Points_By');
            }
            if($this->testPost(post('Maximum_Products')) == true){
                $this->program->maximum_product_value = post('Maximum_Products');
            }
            if($this->testPost(post('Maximum_Points')) == true){
                $this->program->points_limit = post('Maximum_Points');
            }
            $this->program->can_buy_points = post('Can_Buy_Points');
            $this->program->timezone = post('Timezone');
            $this->program->celebrate_birthdays = post('Celebrate_Birthdays');
            $this->program->module_allow_posts = post('Posts_Module');
            $this->program->module_allow_awards = post('Awards_Module');
            $this->program->module_allow_users = post('Profiles_Module');
            $this->program->module_allow_activity_feed = post('Activity_Module');
            $this->program->module_allow_program_tools = post('Program_Tools_Module');
            $this->program->module_allow_results = post('Results_Module');
            $this->program->save();
            $this->navoption = 'generaldetails';
            $this->pageCycle();
            $result['updatesucess'] = "Program updated.";
            $result['html']['#html1target'] = $this->html1;
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdatePaymentMethod()
    {
        $this->pageCycle();
        $client = new \GuzzleHttp\Client();
        $response = post('responsedata');
        $billingContact = BillingContact::find(post('id'));
        if($billingContact == null){
            $billingContact = new BillingContact;
        }
        $billingContact->trackingcategoryname = 'user';
        $billingContact->name = $this->user->full_name.'-'.time();
        $billingContact->firstname = post('First_Name');
        $billingContact->lastname =  post('Surname');
        $billingContact->emailaddress = post('Email');
        $billingContact->defaultCurrent = $response['creditCard']['maskedCardNumber'];
        $billingContact->payment_terms = $response['creditCard']['cardScheme'];
        $billingContact->payway_last_token = $response['singleUseTokenId'];
        $billingContact->save();
        $checkexists = $client->get('https://api.payway.com.au/rest/v1/customers/xtend-'.$billingContact->id,
            [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Accept' => 'application/json',
                ],
                'auth' =>  [$this->config['secretAPI'], ''],
                'http_errors' => false,
            ]
        );
        if($checkexists->getStatusCode() != 200){
            $form_params = $this->config['form_params'];
            $form_params['singleUseTokenId'] = $response['singleUseTokenId'];
            $form_params['customerName'] = $billingContact->name;
            $form_params['emailAddress'] = $billingContact->emailaddress;
            $form_params['phoneNumber'] = post('phonenumber');
            $res = $client->put('https://api.payway.com.au/rest/v1/customers/xtend-'.$billingContact->id,
                [
                    'headers' => [
                        'Cache-Control' => 'no-cache',
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'auth' =>  [$this->config['secretAPI'], ''],
                    'form_params' => $form_params,
                ]
            );
        } else {
            $form_params = $this->config['form_params'];
            $form_params['singleUseTokenId'] = $response['singleUseTokenId'];
            $res = $client->put('https://api.payway.com.au/rest/v1/customers/xtend-'.$billingContact->id.'/payment-setup',
                [
                    'headers' => [
                        'Cache-Control' => 'no-cache',
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'auth' =>  [$this->config['secretAPI'], ''],
                    'form_params' => $form_params,
                ]
            );
        }
        $this->program->billingcontact_id = $billingContact->id;
        $this->program->save();
        $this->program->load('billingcontact');
        $this->navoption = 'billingdetails';
        $this->pageCycle();
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        $result['updatesucess'] = "Billing Details Updated.";
        $result['paywayrun'] = 1;
        return $result;
    }

    public function onUpdateAddress()
    {
        try{
            $address = $this->program->address;
            if($address == null){
                $address = new Address;
            }
            $address->business_name = post('Business_Name');
            $address->attn_name = post('Attn__Name');
            $address->unit_number = post('Unit_Number');
            $address->floor = post('Floor');
            $address->street_number = post('Street_Number');
            $address->street_name = post('Street_Name');
            $address->suburb_name = '';
            $address->city = post('City');
            $address->postcode = post('Postcode');
            $address->state = post('State');
            $address->type = 'Program';
            $address->country_id = 1;
            $address->save();
            $this->program->address_id = $address->id;
            $this->program->save();
            $this->navoption = 'address';
            $this->pageCycle();
            $result['updatesucess'] = "Address updated.";
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onManageTeam()
    {
        try{
            $team = Team::find(post('id'));
            if(!$team){
                $team = new Team;
            }
            $team->managed = true;
            if($team){
                $this->html2 = $this->renderPartial('@teamupdate',
                    [
                        'team' => $team,
                        'otherteams' => $this->user->currentProgram->teams,
                    ]
                );
                $result['html']['#html2target'] = $this->html2;
                return $result;
            } else {
                $result['manualerror'] = "Unable to retrieve Team record.";
                return $result;
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdateTeam()
    {
        try{
            $team = Team::find(post('id'));
            $create = false;
            if(!$team){
                $team = new Team;
                $team->program_id = $this->program->id;
                $create = true;
            }
            if($team){
                if($this->testPost(post('Name')) == true){
                    $team->name = post('Name');
                }
                if($this->testPost(post('External_Reference')) == true){
                    $team->external_reference = post('External_Reference');
                }
                if($this->testPost(post('Primary_Color')) == true){
                    $team->primary_color = post('Primary_Color');
                }
                if($this->testPost(post('Secondary_Color')) == true){
                    $team->secondary_color = post('Secondary_Color');
                }
                if($this->testPost(post('Description')) == true){
                    $team->description = post('Description');
                }
                $team->save();
                if($this->testPost(post('Parent')) == true){
                    $parent = Team::find(post('Parent'));
                    $team->makeChildOf($parent);
                    $team->save();
                } else {
                    if($create == false){
                        $team->makeRoot();
                    }
                }
                if($this->testPost(post('Children')) == true){
                    $teamsarray = explode(",",post('Children'));
                    foreach($teamsarray as $id){
                        $child = Team::find($id);
                        $child->makeChildOf($team);
                        $child->save();
                    }
                }
                $team->save();
                $team->managed = true;
                $this->html2 = $this->renderPartial('@teamupdate',
                    [
                        'team' => $team,
                        'otherteams' => $this->user->currentProgram->teams,
                    ]
                );
                $result['html']['#html2target'] = $this->html2;
                $result['updatesucess'] = "Team updated.";
                return $result;
            } else {
                $result['manualerror'] = "Unable to retrieve Team record.";
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onDeleteTeam()
    {
        try{
            $team = Team::find(post('id'));
            if($team){
                $team->delete();
                $this->navoption = 'teams';
                $this->pageCycle();
                $result['html']['#html2target'] = $this->html2;
                $result['updatesucess'] = "Team deleted.";
            } else {
                $result['manualerror'] = "Unable to retrieve Team record.";
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdateOrganization()
    {
        try{
            $organization = Organization::find(post('id'));
            if(empty($organization)){
                $organization = new Organization;
            }
            if($organization){
                if($this->testPost(post('Name')) == true){
                    $organization->name = post('Name');
                }
                if($this->testPost(post('External_Reference')) == true){
                    $organization->external_reference = post('External_Reference');
                }
                if($this->testPost(post('Primary_Color')) == true){
                    $organization->primary_color = post('Primary_Color');
                }
                if($this->testPost(post('Secondary_Color')) == true){
                    $organization->secondary_color = post('Secondary_Color');
                }
                if($this->testPost(post('Description')) == true){
                    $organization->description = post('Description');
                }
                $organization->save();
                $this->program->organization_id = $organization->id;
                $this->program->save();
                $this->navoption = 'organization';
                $this->pageCycle();
                $result['html']['#html2target'] = $this->html2;
                $result['updatesucess'] = "Organization updated.";
            } else {
                $result['manualerror'] = "Unable to retrieve Organization record.";
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onManageNewUser()
    {
        try{
            $user = new User;
            $user->managed = true;
            if($user){
                $this->html2 = $this->renderPartial('@programcreateuser',
                    [
                        'user' => $user,
                        'teams' => $this->user->currentProgram->teams,
                    ]
                );
                $result['html']['#html2target'] = $this->html2;
                return $result;
            } else {
                $result['manualerror'] = "Unable to retrieve User record.";
                return $result;
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onManageUser()
    {
        try{
            $user = User::find(post('id'));
            $user->managed = true;
            $user->currentteams = $user->teams()->where('program_id','=',$this->program->id)->pluck('id')->toArray();
            if($user){
                $this->html2 = $this->renderPartial('@programupdateuser',
                    [
                        'user' => $user,
                        'teams' => $this->user->currentProgram->teams,

                    ]
                );
                $result['html']['#html2target'] = $this->html2;
                return $result;
            } else {
                $result['manualerror'] = "Unable to retrieve User record.";
                return $result;
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onUpdateUser()
    {
        try{
            $user = User::find(post('id'));
            if($user){
                if($this->testPost(post('Teams')) == true){
                    if($this->testPost(post('Company_Position')) == true){
                        $user->company_position = post('Company_Position');
                    }
                    if($this->testPost(post('First_Name')) == true){
                        $user->name = post('First_Name');
                    }
                    if($this->testPost(post('Surname')) == true){
                        $user->surname = post('Surname');
                    }
                    if($this->testPost(post('First_Name')) == true && $this->testPost(post('Surname')) == true){
                        $user->full_name =  post('First_Name').' '.post('Surname');
                    }
                    if($this->testPost(post('Business_Name')) == true){
                        $user->business_name = post('Business_Name');
                    }
                    if($this->testPost(post('Email')) == true){
                        $user->email = post('Email');
                    }
                    if($this->testPost(post('Phone_Number')) == true){
                        $user->phone_number = post('Phone_Number');
                    }
                    if($this->testPost(post('Birthday')) == true){
                        $user->new_birthday = new Carbon(post('Birthday'));
                    }
                    if($this->testPost(post('Tenure')) == true){
                        $user->new_tenure = new Carbon(post('Tenure'));
                    }
                    $user->save();
                    if($this->testPost(post('Teams')) == true){
                        $teamsarray = explode(",",post('Teams'));

                        $otherteams = $user->teams()->where('program_id','!=',$this->program->id)->pluck('id')->toArray();
                        $merge = array_merge($teamsarray,$otherteams);
                        $user->teams()->sync($merge);
                    }
                    $user->save();
                    $user->managed = true;
                    $user->currentteams = $user->teams()->where('program_id','=',$this->program->id)->pluck('id')->toArray();
                    $this->html2 = $this->renderPartial('@programupdateuser',
                        [
                            'user' => $user,
                            'teams' => $this->user->currentProgram->teams,

                        ]
                    );
                    $result['html']['#html2target'] = $this->html2;
                    $result['updatesucess'] = "User updated.";
                } else {
                    $result['manualerror'] = "User must belong to atleast one team to proceed.";
                }
            } else {
                $result['manualerror'] = "Unable to retrieve User record.";
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onCreateUser()
    {
        try{
            $user = new User;
            $emailuser = User::where('email','=',post('Email'))->first();
            if($emailuser){
                $result['manualerror'] = "E-mail already taken.";
            }elseif($user){
                if($this->testPost(post('Teams')) == true){
                    if($this->testPost(post('Company_Position')) == true){
                        $user->company_position = post('Company_Position');
                    }
                    if($this->testPost(post('First_Name')) == true){
                        $user->name = post('First_Name');
                    }
                    if($this->testPost(post('Surname')) == true){
                        $user->surname = post('Surname');
                    }
                    if($this->testPost(post('First_Name')) == true && $this->testPost(post('Surname')) == true){
                        $user->full_name =  post('First_Name').' '.post('Surname');
                    }
                    if($this->testPost(post('Business_Name')) == true){
                        $user->business_name = post('Business_Name');
                    }
                    if($this->testPost(post('Email')) == true){
                        $user->email = post('Email');
                    }
                    if($this->testPost(post('Phone_Number')) == true){
                        $user->phone_number = post('Phone_Number');
                    }
                    if($this->testPost(post('Birthday')) == true){
                        $user->new_birthday = new Carbon(post('Birthday'));
                    }
                    if($this->testPost(post('Tenure')) == true){
                        $user->new_tenure = new Carbon(post('Tenure'));
                    }
                    $user->current_program_id = $this->program->id;
                    $pword = 'randompword'.rand().'-'.rand();
                    $user->password = $pword;
                    $user->password_confirmation = $pword;
                    $user->is_activated = true;
                    $user->activated_at = new Carbon;
                    $user->save();
                    $user->programs()->add($this->program);
                    if($this->testPost(post('Teams')) == true){
                        $teamsarray = explode(",",post('Teams'));
                        $user->teams()->sync($teamsarray);
                    }
                    $user->save();
                    $user->managed = true;
                    $user->currentteams = $user->teams()->where('program_id','=',$this->program->id)->pluck('id')->toArray();
                    $this->html2 = $this->renderPartial('@programupdateuser',
                        [
                            'user' => $user,
                            'teams' => $this->user->currentProgram->teams,

                        ]
                    );
                    $result['html']['#html2target'] = $this->html2;
                    $result['updatesucess'] = "User created.";
                } else {
                    $result['manualerror'] = "User must belong to atleast one team to proceed.";
                }
            } else {
                $result['manualerror'] = "Unable to retrieve User record.";
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onDeleteUser()
    {
        try{
            $user = User::find(post('id'));
            if($user){
                $user->delete();
                $this->navoption = 'users';
                $this->pageCycle();
                $result['html']['#html2target'] = $this->html2;
                $result['updatesucess'] = "User deleted.";
            } else {
                $result['manualerror'] = "Unable to retrieve User record.";
            }
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onTransferPoints()
    {
        try{
            if($this->testPost(post('User')) == true){
                $user = User::find(post('User'));
            }
            if($this->testPost(post('Points_Amount')) == true){
                $points_amount = intval(post('Points_Amount'));
            }
            $message = post('Message');
            if(!empty($user) && !empty($points_amount)){
                $this->getProgramPointsLedgers();
                if($this->availablepoints >= 1 && $points_amount >= 1){
                    $user->current_program_id = $this->program->id;
                    $user->runMyPoints('true');
                    $newLedger = new PointsLedger;
                    $newLedger->points_value = $points_amount;
                    $newLedger->user_id = $user->id;
                    $newLedger->program = $this->program->id;
                    $newLedger->type = 1;
                    $newLedger->save();
                    $newLedger = new PointsLedger;
                    $newLedger->points_value = $points_amount;
                    $newLedger->user_id = $user->id;
                    $newLedger->program = $this->program->id;
                    $newLedger->type = 6;
                    $newLedger->save();
                    $result['updatesucess'] = $points_amount.' '.$this->program->points_name." transfered to ".$user->full_name.".";

                    /** REPLACE THIS **/

                    if($user && $user->currentProgram->new_transfer_send == true){

                        $roles = [1,3,4];
                        //$backendUsers = Db::table('backend_users')->whereIn('role_id', $roles)->get(['email'])->pluck('email')->toArray();
                        $backendUsers = ['hq@xtendsystem.com'];
                        $toemail = $user->email;
                        //$toemail = 'hq@xtendsystem.com';

                        $receiverfullname = $user->full_name;
                        $programname = $user->currentProgram->name;
                        $programpointsname = $user->currentProgram->points_name;
                        $userpointsvalue = $points_amount;
                        $totalloop = $points_amount;
                        $bodymessage = 'Your hard work has been recognized. You Received '.$points_amount.' '.$this->program->points_name.'!';
                        $bodymessage .= $message;

                        if ($user->currentProgram->login_image){
                            $program_image_url = $user->currentProgram->login_image->path;
                        } else {
                            $program_image_url = null;
                        }

                       if($user->currentProgram->new_transfer_template != null && $user->currentProgram->new_transfer_template != '' && $user->currentProgram->new_transfer_template != 1){
                            $template = $user->currentProgram->new_transfer_template;
                        } else {
                            $template = 'xtenddefault-transfer';
                        }

                        $this->createTransferMessage($user->id,$this->user->id,$bodymessage);

                        $template = new Template($template);
                        $message = new \Addgod\MandrillTemplate\Mandrill\Message();
                        $message->setSubject("A new $programpointsname transfer!");

                        $vars = [
                            'programname' => $programname,
                            'pointsname' => $programpointsname,
                            'pointsvalue' => $userpointsvalue,
                            'programimageurl' => $program_image_url,
                            'receiverfullname' => $receiverfullname,
                            'message' => $bodymessage,
                            'transferamount' => $totalloop,

                        ];

                        $message->setFromEmail('noreply@xtendsystem.com');
                        $message->setFromName('Xtend System');
                        $message->setMergeVars($vars);

                        $recipient = new Recipient($toemail, null, Recipient\Type::TO);
                        $recipient->setMergeVars($vars);
                        $message->addRecipient($recipient);

                        foreach ($backendUsers as $ccemail) {
                            $ccRecipient = new Recipient($ccemail, null, Recipient\Type::CC);
                            $message->setMergeVars($vars);
                            $message->addRecipient($ccRecipient);
                        }

                        MandrillTemplateFacade::send($template, $message);
                    }
                } else {
                    $result['manualerror'] = "No Points Available To Transfer Or Not Points Selected To Transfer.";
                }
            } else {
                $result['manualerror'] = "User Or Amount Value Not Found Or Incompatable.";
            }
            $this->navoption = 'transfers';
            $this->pageCycle();
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function onTransferPrize()
    {
        try{
            $prize = Prize::find(post('id'));
            $winners = $prize->userwinners->count();
            $points_amount = $prize->prize_value;
            $points_amount_total = $prize->prize_value * $winners;
            if(!empty($points_amount)){
                $this->getProgramPointsLedgers();
                if($this->availablepoints >= 1 && $points_amount_total >= 1){
                    $updatesucess = '<ul>';
                    foreach($prize->userwinners as $user){
                        $user->current_program_id = $this->program->id;
                        $user->runMyPoints('true');
                        $newLedger = new PointsLedger;
                        $newLedger->points_value = $points_amount;
                        $newLedger->user_id = $user->id;
                        $newLedger->program = $this->program->id;
                        $newLedger->type = 1;
                        $newLedger->save();
                        $newLedger = new PointsLedger;
                        $newLedger->points_value = $points_amount;
                        $newLedger->user_id = $user->id;
                        $newLedger->program = $this->program->id;
                        $newLedger->type = 6;
                        $newLedger->save();
                        $updatesucess .= '<li>'.$points_amount.' '.$this->program->points_name." transfered to ".$user->full_name.".</li>";
                    }
                    $updatesucess .= '</ul>';
                    $prize->istransferred = 1;
                    $prize->save();
                    $result['updatesucess'] = $updatesucess;
                } else {
                    $result['manualerror'] = "No Points Available To Transfer Or Not Points Selected To Transfer.";
                }
            } else {
                $result['manualerror'] = "User Or Amount Value Not Found Or Incompatable.";
            }
            $this->navoption = 'prizes';
            $this->pageCycle();
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    public function createTransferMessage($receiverid,$senderid,$messagetext)
    {

        $message = new Message;
        $receiver = User::find($receiverid);
        $message->receiver_id = $receiver->id;
        $message->receiver_fullname = $receiver->full_name;
        if($receiver->avatar){
            $message->receiver_thumb_path = $receiver->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $message->receiver_thumb_path = null;
        }
        $sender = User::find($senderid);
        $message->sender_id = $sender->id;
        $message->sender_fullname = $sender->full_name;
        if($sender->avatar){
            $message->sender_thumb_path = $sender->avatar->getThumb(100,100, ['mode' => 'crop']);
        } else {
            $message->sender_thumb_path = null;
        }
        $message->message_text = $messagetext;

        $program = Program::find($sender->current_program_id);
        $message->program_id = $program->id;
        $message->program_name = $program->name;
        $message->program_points_name = $program->points_name;
        $message->program_points_multiple_type = $program->program_markup_type;
        $message->program_points_multiple_integer = $program->scale_points_by;
        if ($program->login_image){
            $message->program_image_path = $program->login_image->path;
        } else {
            $message->program_image_path = null;
        }
        $message->save();
    }

    public function onSingleActivateUser(){
        $id = post('id');
        $user = User::find($id);
        if($user){
            $user->activationProgramInvite($user->currentProgram);
        }
    }

    public function onCreateTransaction()
    {
        $transaction = new Transaction;
        $transaction->value = post('dollarvalue');
        $transaction->type = 'points_purchase_program';
        $transaction->user_id = $this->user->id;
        $transaction->program_id = $this->program->id;
        $transaction->billing_contact_id = $this->program->billingcontact_id;
        $transaction->save();
        $this->navoption = 'programpoints';
        $this->pageCycle();
        $result['html']['#html1target'] = $this->html1;
        $result['html']['#html2target'] = $this->html2;
        $result['updatesucess'] = "Points Add To Program.";
        return $result;
    }

    public function onNominationApproval()
    {
        $nomination = Nomination::find(post('id'));
        if($nomination){
            if($nomination->approved_at == null){
                $nomination->approved_at = new Carbon();
                $nomination->approved_user_id = $this->user->id;
            } else {
                $nomination->approved_at = null;
                $nomination->approved_user_id = null;
            }
            $nomination->save();
            $this->navoption = 'nominations';
            $this->pageCycle();
            $result['updatesucess'] = "Nomination Updated.";
            $result['html']['#html2target'] = $this->html2;
            return $result;
        } else {
            $result['manualerror'] = "Nomination Not Found.";
        }
        return $result;
    }

    public function onReverseTransfer()
    {
        $this->pageCycle();
        $ledger = PointsLedger::find(post('id'));
        if($ledger){
            $thisuser = $ledger->user()->withTrashed()->first();
            $thisuser->runMyPoints(true);
            if(isset($thisuser->points_json[$this->program->id]) && isset($thisuser->points_json[$this->program->id]['points'])){
                $points = $thisuser->points_json[$this->program->id]['points'];
                if($points >= $ledger->points_value){
                    $ledger->type = 7;
                    $ledger->save();
                    $newLedger = new PointsLedger;
                    $newLedger->points_value = $ledger->points_value;
                    $newLedger->user_id = $thisuser->id;
                    $newLedger->program = $ledger->program->id;
                    $newLedger->type = 2;
                    $newLedger->save();
                    $this->navoption = 'transfers';
                    $this->pageCycle();
                    $result['updatesucess'] = "Points Allocation Updated.";
                    $result['html']['#html2target'] = $this->html2;
                    return $result;
                } else {
                    $result['manualerror'] = "User Does Not Have Enough Points Available To Process Reversal.";
                }
            } else {
                $result['manualerror'] = "Problem Finding Program Points Records For This User.";
            }
        } else {
            $result['manualerror'] = "Points Allocation Not Found.";
        }
        return $result;
    }

    public function onExportNominations()
    {

        $this->navoption = 'nominations';
        $this->pageCycle();

        $filename = "nominations.csv";
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Created At',
            'Approved At',
            'Award',
            'Nomination Target',
            'Nominating User',
            'Question Answers',
            'Votes',
            'Image Links',
            'File Links',
            'Winner',
        ];
        fputcsv($handle, $outputarray);

        foreach($this->program->nominations as $row) {
            if($row->nomination_file){
                $files = '';
                $filenames = '';
                foreach($row->nomination_file as $document){
                    if(!empty($document['attachment'])){
                        $files .= $document->getPath().' ';
                        $filenames .= $document->file_name.' ';
                    } else {
                        $filenames = 'Empty';
                    }
                }
            } else {
                $filenames = 'Empty';
            }
            if($row->nomination_image){
                $images = '';
                $imagenames = '';
                foreach($row->nomination_image as $document){
                    if(!empty($document['attachment'])){
                        $images .= $document->getPath().' ';
                        $imagenames .= $document->file_name.' ';
                    } else {
                        $imagenames = 'Empty';
                    }
                }
            } else {
                $imagenames = 'Empty';
            }
            if($row->questions_answers){
                $questions = '';
                foreach($row->questions_answers as $key => $value){
                    $key = str_replace('_',' ',$key);
                    $key = str_replace('-',' ',$key);
                    $key = str_replace(',',' ',$key);
                    $key = str_replace(';',' ',$key);
                    $value = str_replace('_',' ',$value);
                    $value = str_replace('-',' ',$value);
                    $value = str_replace(',',' ',$value);
                    $value = str_replace(';',' ',$value);
                    $questions .= $key.' - '.$value;
                }
            } else {
                $questions = 'Empty';
            }

            $name = 'Not Set';
            if($row->award){
                $name = $row->award->name;
            }
            $created_at = new Carbon($row->created_at,$this->user->currentProgram->timezone);
            $approved_at = new Carbon($row->approved_at,$this->user->currentProgram->timezone);
            $rowarray = [
                $created_at->toDateString(),
                $approved_at->toDateString(),
                $name,
                $row->nominee_full_name,
                $row->created_full_name,
                $questions,
                strval($row->votescount),
                $imagenames,
                $filenames,
            ];
            fputcsv($handle, $rowarray);
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'nominations.csv', $headers);
    }

    public function onExportThankyous()
    {

        $this->navoption = 'thankyous';
        $this->pageCycle();

        $filename = "thankyous.csv";
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Created At',
            'Sender Name',
            'Receiver Name',
            'Message',
        ];
        fputcsv($handle, $outputarray);

        foreach($this->program->thankyous as $row) {
            $created_at = new Carbon($row->created_at, $this->user->currentProgram->timezone);
            $rowarray = [
                $created_at->toDateString(),
                $row->sender_fullname,
                $row->receiver_fullname,
                $row->thankyou_text,
            ];
            fputcsv($handle, $rowarray);
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'thankyous.csv', $headers);
    }

    public function onExportUsersPointsLedger()
    {

        $this->navoption = 'userspoints';
        $this->pageCycle();

        $filename = "userspoints.csv";
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Created At',
            'Type',
            'Dollar Value',
            'Points Value',
            'User ID',
            'User Name',
            'User Email',
        ];
        fputcsv($handle, $outputarray);

        foreach($this->program->pointsledgers as $row) {
            $username = 'Empty';
            $userid = 'Empty';
            $useremail = 'Empty';
            if($row->user){
                $userid = $row->user->id;
                $username = $row->user->full_name;
                $useremail = $row->user->email;
            }
            $rowarray = [
                $row->created_at,
                $row->getTypeAttribute(),
                $row->dollar_value,
                $row->points_value,
                $userid,
                $username,
                $useremail,
            ];
            fputcsv($handle, $rowarray);
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'thankyous.csv', $headers);
    }

    public function onExportUsers()
    {

        $this->navoption = 'users';
        $this->pageCycle();

        $filename = "users.csv";
        $handle = fopen($filename, 'w+');
        $outputarray = [
            'Created At',
            'Last Log In',
            'Name',
            'Business Name',
            'Title',
            'Email',
            'Current Points',
            'Birthday',
            'Start Date',
            'Teams',
        ];
        fputcsv($handle, $outputarray);

        foreach($this->users as $row) {
            $teams = '';
            foreach($row->teams as $team){
                $teams .= $team->name.' ';
            }
            $rowarray = [
                $row->created_at,
                $row->last_seen,
                $row->full_name,
                $row->business_name,
                $row->company_position,
                $row->email,
                $row->current_points,
                $row->new_birthday,
                $row->new_tenure,
                $teams,
            ];
            fputcsv($handle, $rowarray);
        }
        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'users.csv', $headers);
    }
}
