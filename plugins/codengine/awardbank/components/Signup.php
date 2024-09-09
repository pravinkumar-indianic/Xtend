<?php namespace Codengine\Awardbank\Components;

use Codengine\Awardbank\Models\Organization;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Permission;
use Codengine\Awardbank\Models\Category;
use Codengine\Awardbank\Models\BillingContact;
use RainLab\User\Models\User;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use stdClass;
use System\Helpers\DateTime as DateTimeHelper;

class Signup extends ComponentBase
{

    /** MODELS **/

    public $user;
    public $container;
    public $flushtop;
    public $flushbottom;
    public $publishableAPI;
    public $secretAPI;
    public $component1;
    public $component2;

    public function componentDetails()
    {
        return [
            'name' => 'Sign Up',
            'description' => 'Create A Sign Up',
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
        $this->addCss('assets/jquery-ui-1.12.1.custom/jquery-ui.min.css');
        $this->addJs('assets/jquery-ui-1.12.1.custom/jquery-ui.min.js');   
        $this->addJs('/plugins/codengine/awardbank/assets/js/spectrum.js');
        $this->addCss('/plugins/codengine/awardbank/assets/css/spectrum.css');        

        /** INTERNAL MATT TESTER **/

        //$this->publishableAPI = 'T11357_PUB_ujtn84rty6t2unbqn2a9knd2nqeatfbmyiddifaqxrpbiyd3w4ufkzw6ewiw';
        //$this->secretAPI = 'T11357_SEC_77ekqucajb3wfbmzrrrki2cituwk5smyhg26n9vryfn7arg7rqtrzuswcks8';

        /** PRODUCTION EVT **/

        $this->publishableAPI = 'Q16311_PUB_x2sg5t94fpwr8p98zagc4xea9rkhgx2gxyaji9cxiwzb5rnp6mkhkurrup5d';
        $this->secretAPI = 'Q16311_SEC_wtiua4knw9r8t8u8knkqsa9iquram2nxfkg9cm3sq95czwgtkwgkub6573c6';

    }

    public function onRun()
    {

        $this->addJs('https://api.payway.com.au/rest/v1/payway.js');
        $this->container = $this->property('container');
        $this->flushtop = $this->property('flushtop');
        $this->flushbottom = $this->property('flushbottom');

    }

    public function onCreateASignUp()
    {


        if(post('programterritory') != ''){
            $territory = post('programterritory');
        } else {
            $territory = 'AU';
        }

        $newuser = new User;
        $newuser->is_activated = 1;
        $newuser->activated_at = now();
        $newuser->name = post('firstname');
        $newuser->surname = post('surname');
        $newuser->email = post('email');
        $newuser->password =  post('password');
        $newuser->password_confirmation = post('password');
        $newuser->can_buy_points = 1;
        $newuser->phone_number = post('phonenumber');
        $newuser->current_territory = post('programterritory');
        $newuser->save();

        $neworganization = new Organization;
        $neworganization->name = post('businessname');
        $neworganization->primary_color = post('primaryhex');
        $neworganization->secondary_color = post('secondaryhex');
        $neworganization->can_buy_points = 1;
        $neworganization->save();

        $permission = new \Codengine\Awardbank\Models\Permission();
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->add($newuser);
        $permission->organizations()->add($neworganization);

        $newprogram = new Program;
        $newprogram->name = post('programname');
        $newprogram->organization =  post('businessname');
        $newprogram->primary_color = post('primaryhex');
        $newprogram->secondary_color = post('secondaryhex');
        $newprogram->territory = $territory;
        $newprogram->can_buy_points = 1;
        $newprogram->scale_points_by = 2;
        $newprogram->organization_id = $neworganization->id;
        $newprogram->slug = post('subdomain');
        $newprogram->save(null, post('_session_key'));  
        $permission = new \Codengine\Awardbank\Models\Permission();
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->add($newuser);
        $permission->programs()->add($newprogram);

        $excludegiftvouchers = Category::find(407);
        $excludegiftvouchers->program_exclusions()->add($newprogram);
        $newprogram->save();

        $excludeevents = Category::find(511);
        $excludeevents->program_exclusions()->add($newprogram);
        $newprogram->save();

        $permission = new \Codengine\Awardbank\Models\Permission();
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->add($newprogram);
        $permission->categories()->attach(477);

        $structure = json_decode(post('structure'),true);

        if(is_array($structure)){
            foreach($structure as $region){
                if(isset($region['id'])){
                    $createdRegion = new Region;
                    $createdRegion->name = $region['name'];
                    $createdRegion->primary_color = 'black';
                    $createdRegion->secondary_color = 'black';
                    $createdRegion->program_id = $newprogram->id;
                    $createdRegion->save();
                }
                if(isset($region['children'])){
                    foreach($region['children'] as $team){
                        if(isset($team['id'])){
                            $createdTeam = new Team;
                            $createdTeam->name = $team['name'];
                            $createdTeam->primary_color = 'black';
                            $createdTeam->secondary_color = 'black';
                            $createdTeam->save();
                            $createdTeam->regions()->attach($createdRegion->id);                        
                        }
                        if(isset($team['children'])){
                            foreach($team['children'] as $user){
                                if(isset($user['id'])){
                                    if($user['id'] == 'me'){
                                        $newuser->teams()->attach($createdTeam->id);
                                    } else {
                                        $testuser = User::where('email','=', $user['email'])->first();
                                        if($testuser != null){
                                            $createdUser = $testuser;
                                        } else {
                                            $createdUser = Auth::register([
                                                'name' => $user['name'],
                                                'email' => $user['email'],
                                                'password' => 'changeme',
                                                'password_confirmation' => 'changeme',
                                            ]);
                                        }
                                        $createdUser->teams()->attach($createdTeam->id);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $response = post('responsedata');

        $billingContact = new BillingContact;
        $billingContact->trackingcategoryname = 'program';
        $billingContact->name = $newprogram->name.'-'.time();
        $billingContact->firstname = $newuser->name;
        $billingContact->lastname = $newuser->surname;
        $billingContact->emailaddress = $newuser->email;
        $billingContact->organization_id = $neworganization->id;
        $billingContact->defaultCurrent = $response['creditCard']['maskedCardNumber'];
        $billingContact->payment_terms = $response['creditCard']['cardScheme'];
        $billingContact->save();

        $newprogram->billingcontact_id = $billingContact->id;
        $newprogram->save();

        $client = new \GuzzleHttp\Client();
        $res = $client->put('https://api.payway.com.au/rest/v1/customers/xtend-'.$billingContact->id, 
            [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'auth' =>  [$this->secretAPI, ''],

                /**
                'form_params' => [
                    'singleUseTokenId' => $response['singleUseTokenId'],
                    'merchantId' => 'TEST',
                    'customerName' => $billingContact->name,
                    'emailAddress' => $billingContact->emailaddress,
                    'phoneNumber' => post('phonenumber'),
                ],
                **/

                'form_params' => [
                    'singleUseTokenId' => $response['singleUseTokenId'],
                    'merchantId' => '24097800',
                    'customerName' => $billingContact->name,
                    'emailAddress' => $billingContact->emailaddress,
                    'phoneNumber' => post('phonenumber'),
                ],
                
        ]);
        
        $status = $res->getStatusCode(); // 200
        $body = $res->getBody(); // { "type": "User", ....

        $newuser->bounceDownRelations();
        $newuser->sendEmailActivation();
        Auth::login($newuser);        

    }

    public function onCheckEmailUnique(){

        $user = User::where('email','=', post('email'))->withTrashed()->first();

        if($user == null){
            $result['found'] = false;
        } else {
            $result['found'] = true;            
        }

        return $result;

    }

    public function onCreatePaymentCustomer(){

        $response = post('responsedata');
        $client = new \GuzzleHttp\Client();
        $res = $client->put('https://api.payway.com.au/rest/v1/customers/xtend-1', 
            [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'auth' =>  [$this->secretAPI, ''],

                'form_params' => [
                    'singleUseTokenId' => $response['singleUseTokenId'],
                    'merchantId' => 'TEST',
                    'customerName' => post('firstname').' '.post('surname'),
                    'emailAddress' => post('email'),
                    'phoneNumber' => post('phonenumber'),
                ],

                /**
                'form_params' => [
                    'singleUseTokenId' => $response['singleUseTokenId'],
                    'transactionType' => 'payment',
                    'principalAmount' => '1',
                    'currency' => 'aud',
                    'merchantId' => '24097800',
                    'customerNumber' => '1234'
                ],
                **/
        ]);

        $status = $res->getStatusCode(); // 200
        $body = $res->getBody(); // { "type": "User", ....

    }
}
