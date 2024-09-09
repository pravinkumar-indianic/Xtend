<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Prize as Prize;
use Seeder;

class PrizeSeeder extends Seeder
{
    public function run()
    {

        $prize = Prize::create([
            'award_id'		=> 1,
            'winner_id'		=> 0,
            'name'			=> 'First Prize',
            'order'			=> 1
        ]);

        $prize2 = Prize::create([
            'award_id'		=> 1,
            'winner_id'		=> 0,
            'name'			=> '2nd Prize',
            'order'			=> 1
        ]);
    }
}