<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Like as Like;
use Seeder;

class LikeSeeder extends Seeder
{
    public function run()
    {

        $Like1 = Like::create([
            'user_id'  => '1'
        ]);

        $Like2 = Like::create([
            'user_id'  => '2'
        ]);

    }
}