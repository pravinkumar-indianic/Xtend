<?php namespace Codengine\Awardbank\Models;

use Codengine\Awardbank\Models\XeroAPI;
use Carbon\Carbon;
use Model;
use DB;

/**
 * Model
 */
class Program extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Sluggable;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'name'];

    protected $fillable = [
        'id',
        'name',
        'description',
        'primary_color',
        'secondary_color',
        'start_date',
        'start_date',
        'scale_points_by',
        'points_name',
        'program_markup_type',
        'program_markup_integer',
        'can_buy_points',
        'points_limit',
        'external_reference',
    ];

    /*
     * Validation
     */

    public $rules = [
        'name'    => 'required|string',
        'description'   => 'nullable|string',
        'primary_color' => 'required|string',
        'secondary_color' => 'required|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'scale_points_by' => 'nullable|integer',
        'points_name' => 'nullable|string',
        'program_markup_type' => 'nullable|string',
        'program_markup_integer' => 'nullable|integer',
        'can_buy_points' => 'nullable|integer',
        'points_limit' => 'nullable|integer',
        'external_reference' => 'nullable|string',
    ];

    protected $jsonable = [
        'category_exclusion_array',
        'birthday_array',
        'tenure_array',
        'register_form_questions',
        'content_block'
    ];

    protected $casts = [
        'faqs' => 'array'
    ];

    public $belongsTo = [
        'organization' => 'Codengine\Awardbank\Models\Organization',
        'address' => 'Codengine\Awardbank\Models\Address',
        'billingcontact' => ['Codengine\Awardbank\Models\BillingContact'],
    ];

    public $belongsToMany = [
        'product_exclusions' => [
            'Codengine\Awardbank\Models\Product',
            'table' => 'codengine_awardbank_p_p_ex'
        ],
        'category_exclusions' => [
            'Codengine\Awardbank\Models\Category',
            'table' => 'codengine_awardbank_p_c_ex'
        ],
        'users' => [
            'Rainlab\User\Models\User','table' =>
            'codengine_awardbank_u_p'
        ],
        'managers' => [
            'Rainlab\User\Models\User',
            'table' => 'codengine_awardbank_u_pm'
        ],
        'posts' => [
            'Codengine\Awardbank\Models\Post',
            'table'    => 'codengine_awardbank_po_pr',
            'key'      => 'program_id',
            'otherKey' => 'post_id'
        ],
    ];

    public $hasMany = [
        'regions' => ['Codengine\Awardbank\Models\Region'],
        'teams' => ['Codengine\Awardbank\Models\Team'],
        'billing_invoices' => ['Codengine\Awardbank\Models\BillingInvoice'],
        'awards' => ['Codengine\Awardbank\Models\Award'],
        'resultgroups' => ['Codengine\Awardbank\Models\ResultGroup'],
        'pointsledgers' => ['Codengine\Awardbank\Models\PointsLedger'],
        'orders' => [
            'Codengine\Awardbank\Models\Order',
            'key' => 'order_program_id'
        ],
        'nominations' => [
            'Codengine\Awardbank\Models\Nomination',
            'key' => 'program_id'
        ],
        'transactions' => ['Codengine\Awardbank\Models\Transaction'],
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File',
        'feature_image_mobile' => 'System\Models\File',
        'header_icon' => 'System\Models\File',
        'login_image'  => 'System\Models\File',

        'dashboard_image_first' => 'System\Models\File',
        'dashboard_image_second' => 'System\Models\File',

        'reward_banner_image' => 'System\Models\File',
        'faq_banner_image' => 'System\Models\File',

        'social_feed_top_banner' => 'System\Models\File',
        'social_feed_bottom_banner' => 'System\Models\File',
    ];

    public $attachMany = [

        'slider_images' => 'System\Models\File',

    ];

    public $morphToMany = [

        'accessmanager' => ['Rainlab\User\Models\User', 'table' => 'codengine_awardbank_access_manager_allocation', 'name'=>'managable'],

        'viewability' => [
            'Codengine\Awardbank\Models\Permission',
            'table' => 'codengine_awardbank_permission_access_allocation',
            'name'=>'permissionaccessallocatable',
            'scope' => 'isViewable'
        ],
        'permissions' => ['Codengine\Awardbank\Models\Permission', 'table' => 'codengine_awardbank_permission_access_allocation', 'name'=>'permissionaccessallocatable'],
    ];

    /**
        Filter Scopes

    **/

    public function scopeIsActive($query)
    {
        return $query->where('deleted_at', null);
    }

    public function scopeFilterHasOrganization($query, $response){
        $query->whereHas('organization', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasTeams($query, $response){
        $query->whereHas('teams', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasBillingContact($query, $response){
        $query->whereHas('billingcontact', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasUsers($query, $response){
        $query->whereHas('users', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasAddress($query, $response){
        $query->whereHas('address', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasProductExclusions($query, $response){
        $query->whereHas('product_exclusions', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasCategoryExclusions($query, $response){
        $query->whereHas('category_exclusions', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }

    public function scopeFilterHasResultGroups($query, $response){
        $query->whereHas('resultgroups', function ($query) use ($response){
            $query->whereIn('id', $response);
        });
    }


    public function getTimezoneOptions()
    {
        return [
            'Australia/Adelaide' => 'Australia/Adelaide',
            'Australia/Brisbane' => 'Australia/Brisbane',
            'Australia/Broken_Hill' => 'Australia/Broken_Hill',
            'Australia/Currie' => 'Australia/Currie',
            'Australia/Darwin' => 'Australia/Darwin',
            'Australia/Eucla' => 'Australia/Eucla',
            'Australia/Hobart' => 'Australia/Hobart',
            'Australia/Lindeman' => 'Australia/Lindeman',
            'Australia/Lord_Howe' => 'Australia/Lord_Howe',
            'Australia/Melbourne' => 'Australia/Melbourne',
            'Australia/Perth' => 'Australia/Perth',
            'Australia/Sydney' => 'Australia/Sydney',
        ];
    }
    /**

    public function teams($deferred = null)
    {
        if($deferred != null){
            $this->load(['regions.teams' => function($query) use (&$teams, $deferred) {
                $teams = $query->withDeferred($deferred)->get()->unique();
            }]);
        } else {
            $this->load(['regions.teams' => function($query) use (&$teams) {
                $teams = $query->get()->unique();
            }]);
        }

        return $teams;
    }

    public function regionsTeam($deferred = null)
    {

        if($deferred != null){
            $teams = $this->regions()->with(['teams' => function($query) use ($deferred) {
                $query->withDeferred($deferred);
            }])->get();
        } else {
            $teams = $this->regions()->with('teams')->get();
        }

        return $teams;
    }

    **/

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_programs';

    public function beforeSave(){
        $this->slugAttributes();
        $this->saveDownCategoryExclusions();
        if($this->xero_billing_start_date == null){
            $startBilling = new Carbon($this->created_at);
            $startBilling = $startBilling->addDays(14);
            if($startBilling->day > 28){
                $startBilling->addMonth();
                $startBilling->day = 1;
            }
            $this->xero_billing_start_date = $startBilling;
        } else {
            $startBilling = new Carbon($this->xero_billing_start_date);
            if($startBilling->day > 28){
                $startBilling->addMonth();
                $startBilling->day = 1;
            }
            $this->xero_billing_start_date = $startBilling;
        }
        if($this->xero_billing_next_date == null || $this->first_billing_sent == false){
            $this->xero_billing_next_date = $this->xero_billing_start_date;
        }
        if($this->xero_renewal_day_of_month == null){
            $makeCarbon = new Carbon($this->xero_billing_start_date);
            $day = $makeCarbon->day;
            if($day > 28){
                $day = 1;
            }
            $this->xero_renewal_day_of_month = $day;
        }

        $this->xero_renewal_member_count = $this->getMyUserCountAttribute();

        if($this->manual_pricing == false){

            if($this->xero_renewal_member_count <= 10){
                $this->xero_renewal_price_plan = 10;
            } else {
                $this->xero_renewal_price_plan = 6;
            }

        }

        $this->generateJobCodeSuffix();

    }

    public function afterSave()
    {
        //$this->saveJobCode();
    }

    public function getDashboardImageFirst()
    {
        return 'h';
    }

    public function getMyRegionsAttribute()
    {
        $result = '';
        if($this->regions){
            foreach($this->regions as $region){
                $result .= $region->name.', ';
            }
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    public function getMyTeamsAttribute()
    {
        $result = '';
        if($this->regions){
            foreach($this->regions as $region){
                if($region->teams){
                    foreach($region->teams as $team){
                        $result .= $team->name.', ';
                    }
                }
            }
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    public function getMyUserCountAttribute()
    {
        $userarray = [];
        if($this->regions){
            foreach($this->regions as $region){
                if($region->teams){
                    foreach($region->teams as $team){
                        if($team->users){
                            foreach($team->users as $user){
                                if(!in_array($user->id,$userarray)){
                                    $userarray[] = $user->id;
                                }
                            }
                        }
                    }
                }
            }
        }
        $result = count($userarray);
        return $result;
    }

    public function getResultsPositionAttribute()
    {
        //EVT-131 - Requested to display the Dashboard result component on the top of the page for all programs
        /* if (in_array($this->id, [11, 12, 84, 85, 104])) {
            return 'top';
        }
        return 'standard';*/
        return 'top';
    }

    public function getUserRelationContent()
    {
        $userarray = [];
        if($this->regions){
            foreach($this->regions as $region){
                if($region->teams){
                    foreach($region->teams as $team){
                        if($team->users){
                            foreach($team->users as $user){
                                if(!in_array($user,$userarray)){
                                    $userarray[] = $user;
                                }
                            }
                        }
                    }
                }
            }
        }
        $result = $userarray;
        return $result;
    }

    public function getRelationContent($permissions,$entities)
    {
        $results = [];
        if($this->$permissions){
            foreach($this->$permissions as $permission){
                if($permission->$entities){
                    foreach($permission->$entities as $entity){
                        $results[] = $entity;
                    }
                }
            }
        }
        return $results;
    }

    public function getProductRelationContent()
    {
        $results = [];
        if($this->viewability){
            foreach($this->viewability as $view){
                if($view->suppliers){
                    foreach($view->suppliers as $supplier){
                        if($supplier->products){
                            foreach($supplier->products as $product){
                                if(!in_array($product,$results)){
                                    $results[] = $product;
                                }
                            }
                        }
                    }
                }
            }
        }
        if($this->viewability){
            foreach($this->viewability as $view){
                if($view->products){
                    foreach($view->products as $product){
                        if(!in_array($product, $results)){
                            $results[] = $product;
                        }
                    }
                }
            }
        }
        return $results;
    }


    public function getOwnerscheckboxOptions($level = 0){


        $array = [];

        if(post('program_id')){

            $program = Program::where('id','=', post('program_id'))->with('regions.teams.users')->first();

        } else {

            $program = Program::where('id','=', $this->id)->with('regions.teams.users')->first();

        }


        foreach($program->teams as $team){

            if($level == 1){

                $string = $team->name;

                $array[$team->id] = $string;

            } else {

                foreach($team->users as $user){

                    if($user->company_position == '')
                        $user->company_position = 'Staff';

                    $string = $user->full_name. ' - Title: '. $user->company_position .' - Team: ' .$team->name;

                    $array[$user->id] = $string;
                }

            }
        }


        return $array;

    }

    public function saveDownCategoryExclusions(){

        $exclusionArray = [];

        if($this->category_exclusions){
            foreach($this->category_exclusions as $exclusion){

                $exclusionArray[] = $exclusion->id;

                $children = $exclusion->getAllChildren();

                foreach($children as $child) {

                    $exclusionArray[] = $child->id;

                }

            }
        }

        $this->category_exclusion_array = $exclusionArray;

    }

    public function filterFields($fields, $context = null)
    {

        // dd($fields->author);

    }

    public function generateJobCodeSuffix()
    {

        if($this->xero_job_code_suffix == null){
            $suffix = substr($this->name, 0, 3);
            $suffix = strtoupper($suffix).'/AA';
            $this->xero_job_code_suffix = $suffix;
        }

        $result = $this->isTheJobCodeUnique();

        while($result >= 2)
        {
            $suffix = explode("/",$this->xero_job_code_suffix);
            $nextstep = $suffix[1];
            $nextstep++;
            $suffix = $suffix[0].'/'.strtoupper($nextstep);
            $this->xero_job_code_suffix = $suffix;
            $result = $this->isTheJobCodeUnique();
        }

    }
    public function isTheJobCodeUnique()
    {
        $count = DB::table('codengine_awardbank_programs')->where('xero_job_code_suffix','=',$this->xero_job_code_suffix)->count();
        return $count;
    }

    public function saveJobCode()
    {

        $xero = XeroAPI::getXero();

        $trackingcategory = $xero->load('Accounting\\TrackingCategoryOverride')
        ->where('Name', 'Job')
        ->execute();
        $jobcodes = $trackingcategory->first();
        if($jobcodes == null){
            $jobcodes = new \XeroPHP\Models\Accounting\TrackingCategoryOverride($xero);
            $jobcodes->setName('Job');
            $jobcodes->save();
        }
        $newOption = true;
        if($jobcodes->getOptions() != null){
            foreach($jobcodes->getOptions() as $option){
                $optionname = $option->getName();
                if($optionname == $this->xero_job_code_suffix){
                    $newOption = false;
                }
            }
        }
        if($newOption == true){
            $option = new \XeroPHP\Models\Accounting\TrackingCategory\TrackingOption($xero);
            $option->setName($this->xero_job_code_suffix);
            $option->setStatus('ACTIVE');
            $jobcodes->addOption($option);
            $jobcodes->save();
        }
    }

    /** VERSION 2 FUNCTIONS **/

    public function checkIfManager($user)
    {
        $managersarray = $this->managers->pluck('id')->toArray();
        if(in_array($user->id,$managersarray)){
            return true;
        } else {
            return false;
        }
    }
}
