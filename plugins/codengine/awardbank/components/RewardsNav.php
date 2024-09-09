<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Category;
use Auth;
use Session;
use Event;
use Carbon\Carbon;

class RewardsNav extends ComponentBase
{

    /** MODELS **/

    private $user;
    private $categories;
    public $html;

    public function componentDetails()
    {
        return [
            'name' => 'Rewards Nav Component',
            'description' => 'Rewards By Category',
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
            $this->moduleEnabled = Event::fire('xtend.checkModuleValid', [$this->user,'module_allow_rewards'], true);
        }    

    }

    public function onRun()
    {
        $this->addJs('assets/js/RewardsNav140819.js');
        $this->coreLoadSequence();
    }

    /**
        Run the core functions for processing variables and generating html.
    **/

    public function coreLoadSequence()
    {
        $this->loadCategories();   
        $this->navArrayFactory(); 
        $this->generateHtml();
    }

    /**
        Fetch a nested collection of all the categories + children not excluded in the program.
    **/

    protected function loadCategories()
    {
        $this->categories = Category::where('type','=','product')->whereDoesntHave('program_exclusions', function($query){
            $query->where('program_id','=', $this->user->current_program_id);
        })->withCount(['product' => function($query){
            $query->where('active','=', 1)
            ->whereDate('activate_after','<=', Carbon::now())
            ->whereDoesntHave('program_exclusions', function($query2){
                $query2->where('program_id','=', $this->user->current_program_id);
            })
            ->where('territory','=', $this->user->current_territory);
            if($this->user->currentProgram->maximum_product_value > 0){
                $query->where('points_value','<=', $this->user->currentProgram->maximum_product_value);
            }
        }])->getNested();
    }

    /**
        Translated $this->categories into an array specific for the view representation.
    **/

    public function navArrayFactory()
    {
        $navArray = [];
        foreach($this->categories as $category){
            $navArray[$category->id]['name'] = $category->name;
            $navArray[$category->id]['slug'] = $category->slug;           
            $navArray[$category->id]['children'] = [];
            $navArray[$category->id]['productcount'] = $category->product_count;
            foreach($category->getChildren() as $subcategory){
                $navArray[$category->id]['children'][$subcategory->id]['name'] = $subcategory->name;
                $navArray[$category->id]['children'][$subcategory->id]['slug'] = $subcategory->slug;
                $navArray[$category->id]['children'][$subcategory->id]['children'] = []; 
                $navArray[$category->id]['children'][$subcategory->id]['productcount'] = $subcategory->product_count;
                foreach($subcategory->getChildren() as $subsubcategory){
                    $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['name'] = $subsubcategory->name;
                    $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['slug'] = $subsubcategory->slug;
                    $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['children'] = [];
                    $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['productcount'] = $subsubcategory->product_count;
                    foreach($subsubcategory->getChildren() as $subsubsubcategory){
                        $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['children'][$subsubsubcategory->id]['name'] = $subsubsubcategory->name;
                        $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['children'][$subsubsubcategory->id]['slug'] = $subsubsubcategory->slug;
                        $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['children'][$subsubsubcategory->id]['productcount'] = $subsubsubcategory->product_count;

                        $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['productcount'] = $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['productcount'] + $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['children'][$subsubsubcategory->id]['productcount'];
                    }
                    $navArray[$category->id]['children'][$subcategory->id]['productcount'] = $navArray[$category->id]['children'][$subcategory->id]['productcount'] + $navArray[$category->id]['children'][$subcategory->id]['children'][$subsubcategory->id]['productcount'];
                }
                $navArray[$category->id]['productcount'] =  $navArray[$category->id]['productcount'] + $navArray[$category->id]['children'][$subcategory->id]['productcount'];
            }
        }
        $this->categories = $navArray;  
    }

    /**
        Render the html partials to pass back into public vars.
    **/

    public function generateHtml()
    {
        $this->html = $this->renderPartial('@navlist',
            [
                'categories' => $this->categories, 
            ]
        );  
    }
}