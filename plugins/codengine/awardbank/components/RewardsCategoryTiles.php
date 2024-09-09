<?php namespace Codengine\Awardbank\Components;

use \Cms\Classes\ComponentBase;
use Codengine\Awardbank\models\Category;
use Auth;
use Storage;
use Config;

class RewardsCategoryTiles extends ComponentBase
{

    /** MODELS **/

    private $user;
    public $categories;


    public function componentDetails()
    {
        return [
            'name' => 'Rewards Category Tiles',
            'description' => 'Rewards Page Category Tiles Block',
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
    }

    public function onRun()
    {
        $this->categories = Category::where('parent_id','=', null)->where('type','=','product')
        ->whereDoesntHave('program_exclusions', function($query){
            $query->where('program_id','=', $this->user->current_program_id);
        })->get();
    }
}
