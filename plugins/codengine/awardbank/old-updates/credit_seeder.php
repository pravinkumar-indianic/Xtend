<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Credit as Credit;
use Seeder;

class CreditSeeder extends Seeder
{
    public function run()
    {

        $credit = Credit::create([
            'user_id'			=> '1',
            'value'				=> '20'
        ]);
        $credit = Credit::create([
            'user_id'			=> '2',
            'value'				=> '15'
        ]);
    }
}