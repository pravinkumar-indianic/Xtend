<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Nomination as Nomination;
use Seeder;

class NominationSeeder extends Seeder
{
    public function run()
    {

        $nomination = Nomination::create([
            'award_id'                 => '1',
            'nominated_user_id'                 => '1',
            'created_user_id'                 => '2',          
        ]);
    }
}