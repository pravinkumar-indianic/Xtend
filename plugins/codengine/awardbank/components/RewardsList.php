<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Category;
use Codengine\Awardbank\models\Product;
use Auth;
use Session;

use Db;
use Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RewardsList extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $moduleEnabled;
    private $products;
    private $offset = 0;
    private $minprice = 1;
    private $maxprice = 20000;
    private $category = null;
    private $categoryTree = [];
    private $pointsname;
    private $breadcrumbs;
    private $children;
    private $totalResults = null;
    private $sortBy;
    private $searchTerm;
    private $append = false;
    private $redeemFilter = false;

    public $title = 'Rewards';
    public $filters;
    public $list;

    public function componentDetails()
    {
        return [
            'name' => 'Rewards List',
            'description' => 'Rewards List',
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
            $this->user = $this->user->load('currentProgram');
            $this->pointsname = $this->user->currentProgram->points_name;
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_rewards'], true);
        }
    }

    public function onRun()
    {
        if($this->moduleEnabled == true && $this->user){
            $this->addAssets();
            if($this->user->currentProgram->maximum_product_value > 0){
                $this->maxprice = $this->user->currentProgram->maximum_product_value * $this->user->currentProgram->scale_points_by;
            } else {
                $this->maxprice = $this->maxprice * $this->user->currentProgram->scale_points_by;
            }
            $this->coreLoadSequence();
        }
    }

    /**
        Run the core functions for processing variables and generating html.
    **/

    public function coreLoadSequence()
    {
            $this->getCategories();
            $this->getProducts();
            $this->productsFactory();
            $this->generateHtml();
    }

    /**
        Inject required Jss + Css.
    **/

    public function addAssets()
    {
        /** Supports the Slider **/
        $this->addCss('assets/jquery-ui-1.12.1.custom/jquery-ui.min.css');
        $this->addJs('assets/jquery-ui-1.12.1.custom/jquery-ui.min.js');
        /** Page JS **/
        $this->addJs('assets/js/RewardsList0907191.js');
    }

    /**
        Check if category is set from the page param. If so, fetch category and children into flat array. If not, get ALL categories and children into flat array.
    **/

    public function getCategories()
    {
        if(null !== $this->param('category_id'))
        {
            $this->category = Category::where('slug', $this->param('category_id'))->first();
            if (!$this->category)
                $this->category = Category::find($this->param('category_id'));
        }
        if($this->category){
            $this->title = $this->category->name;
            $this->breadcrumbs = $this->category->getParents('id','name');
            $this->children = $this->category->getChildren();
        } else {
            $this->children = Category::where('nest_depth','=',0)->whereDoesntHave('program_exclusions', function($query){
                $query->where('program_id','=', $this->user->current_program_id);
            })->isProduct()->get();
        }
        if($this->category){
            $this->categoryTree = $this->category->getAllChildrenAndSelf()->pluck('id')->toArray();
        }
    }

    /**
        Fetch collection of 12 product records based on filters / settings and store to $this->products
    **/

    public function getProducts()
    {
        if(!empty($this->categoryTree) && empty($this->searchTerm))
        {
            $this->products = Product::where('active','=', 1)
            ->whereDate('activate_after','<=', Carbon::now())
            ->where('territory','=', $this->user->current_territory)
            ->leftJoin('codengine_awardbank_category_allocation', 'codengine_awardbank_category_allocation.entity_id', '=', 'codengine_awardbank_products.id')
            ->where('codengine_awardbank_category_allocation.entity_type','=','Codengine\Awardbank\Models\Product')
            ->whereDoesntHave('program_exclusions', function ($query) {
                $query->where('program_id', '=', $this->user->current_program_id);
            })
            ->where(function($query){
                $query->whereIn('codengine_awardbank_category_allocation.category_id', $this->categoryTree)
                ->whereNotIn('codengine_awardbank_category_allocation.category_id', $this->user->currentProgram->category_exclusion_array);
            })
            ->select('codengine_awardbank_products.*', DB::raw('count(*) as total'))
            ->groupBy('codengine_awardbank_products.id');

        } else {
            $this->products = Product::isValidForUser($this->user)
            ->select('codengine_awardbank_products.*', DB::raw('count(*) as total'))
            ->whereDoesntHave('program_exclusions', function ($query) {
                $query->where('program_id', '=', $this->user->current_program_id);
            })
            ->groupBy('codengine_awardbank_products.id');
        }

        if($this->redeemFilter == '1'){
            $this->minprice = $this->minprice;
            $this->maxprice = $this->user->current_points;
        }

        $calcminprice = ceil($this->minprice / $this->user->currentProgram->scale_points_by);
        $calcmaxprice = ceil($this->maxprice / $this->user->currentProgram->scale_points_by);

        $this->products = $this->products->where('points_value', '>=', $calcminprice)->where('points_value','<=', $calcmaxprice);

        if($this->searchTerm)
        {
            $this->products = $this->products->where(function($query){
                $query->where('name','like','%'.$this->searchTerm.'%');
            });

        }
        if ($this->sortBy == 'asc') {
            $this->products = $this->products->orderBy('name', 'asc');
        } else if($this->sortBy == 'desc') {
            $this->products = $this->products->orderBy('name', 'desc');
        } else if($this->sortBy == 'new') {
            $this->products = $this->products->orderBy('updated_at', 'desc');
        } else if($this->sortBy == 'old') {
            $this->products = $this->products->orderBy('updated_at', 'asc');
        } else if($this->sortBy == 'price-high') {
            $this->products = $this->products->orderBy('points_value', 'desc');
        } else if($this->sortBy == 'price-low') {
            $this->products = $this->products->orderBy('points_value', 'asc');
        } else {
            $this->products = $this->products->orderBy('updated_at', 'desc');
        }

        if(!$this->totalResults){
            $this->totalResults = $this->products->get(['codengine_awardbank_products.id'])->count();
        }

        $this->products = $this->products->limit(12)->offset($this->offset)->get();
    }

    /**
        Transform Product in $this->products based on program + user details for rendering.
    **/

    public function productsFactory()
    {
        $this->products = $this->products->each(function ($product, $key) {
            $product->name = html_entity_decode($product->name);
            $product->description = html_entity_decode($product->description);
            $useravailablepoints = $this->user->current_points;
            $product->points_value = $product->points_value * $this->user->currentProgram->scale_points_by;
            $product->points_name = $this->user->currentProgram->points_name;
            $product->redeemable = false;
            $product->can_buy_points = $this->user->currentProgram->can_buy_points;
            $product->percent = ceil(($useravailablepoints / $product->points_value * 100));
            $product->pointsdif = $product->points_value - $useravailablepoints;
            if($product->can_buy_points == true){
                if($this->user->currentProgram->points_limit >= 1){
                    if($this->user->currentProgram->points_limit < $product->pointsdif){
                        $product->can_buy_points = false;
                    }
                }
            }
            if($product->points_value <= $useravailablepoints ){
                $product->redeemable = true;
                $product->percent = 100;
            }
            if($product->external_image_url != null){
                $product->imagepath = $product->external_image_url;
            } else {
                 $product->imagepath = $product->feature_image ? $product->feature_image->getThumb('auto', 'auto') : '';
            }
            return $product;
        });
    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->filters = $this->renderPartial('@filters',
            [
                'title' => $this->title,
                'breadcrumbs' => $this->breadcrumbs,
                'children' => $this->children,
                'points_name' => $this->user->currentProgram->points_name,
                'minprice' => $this->minprice,
                'maxprice' => $this->maxprice,
            ]
        );

        $this->list = $this->renderPartial('@productslist',
            [
                'products' => $this->products,
                'totalResults' => $this->totalResults,
                'offset' => $this->offset,
            ]
        );
    }

    /**
        AJAX Requests
    **/

    /**
        Catch data sent from List Filters, set variables and re-run core load sequence to return new list html.
    **/

    public function onRefreshListFilter()
    {


        $currentProgram = $this->currentProgram;
        $this->sortBy = $this->testPost(post('sortBy'));
        if($this->testPost(post('lowPrice')) !== null){
            $this->minprice = intval(post('lowPrice'));
        }
        if($this->testPost(post('highPrice')) !== null){
            $this->maxprice = intval(post('highPrice'));
        }
        $this->searchTerm = $this->testPost(post('searchTerm'));
        if($this->testPost(post('offset')) !== null){
            $this->offset = intval(post('offset'));
        }
        $this->append = !$this->testPost(post('refresh'));
        $this->redeemFilter = $this->testPost(post('redeemFilter'));
        $this->coreLoadSequence();

        if($this->append == 'true'){
            $result['append']['#listhtml'] = $this->list;
        } else {
            $result['html']['#listhtml'] = $this->list;


        }
        return $result;

    }

    /**
        Check if the variables passed via AJAX don't contain empty strings or are otherwise empty. Return null if so.
    **/

    public function testPost($input)
    {
        if($input != '' && !empty($input)){
            return $input;
        } else {
            return null;
        }
    }
}
