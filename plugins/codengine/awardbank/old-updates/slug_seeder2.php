<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Team;
use Codengine\Awardbank\Models\Region;
use Codengine\Awardbank\Models\Program;
use Codengine\Awardbank\Models\Organization;
//use RainLab\User\Models\User;
use Seeder;
use Storage;

class SlugSeeder2 extends Seeder
{
    public function run()
    {

        $tags = Team::all();
        foreach ($tags as $tag) {
            $tag->slug = null;
            $tag->slugAttributes();
            $tag->can_buy_points = 1;
            $tag->save();
        }
        
        $cats = Region::all();
        foreach ($cats as $cat) {
            $cat->slug = null;
            $cat->slugAttributes();
            $cat->can_buy_points = 1;
            $cat->save();
        }     

        $awards = Program::all();
        foreach ($awards as $award) {
            $award->slug = null;
            $award->slugAttributes();
            $award->can_buy_points = 1;
            $award->save();
        }  

        $products = Organization::all();
        foreach ($products as $product) {
            $product->slug = null;
            $product->slugAttributes();
            $product->save();
        }

        // $suppliers = User::all();
        // foreach ($suppliers as $supplier) {
        //     $supplier->slug = null;
        //     $supplier->slugAttributes();
        //     $supplier->save();
        // }    

                         
    }
}