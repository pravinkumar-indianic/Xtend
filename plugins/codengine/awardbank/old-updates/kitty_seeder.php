<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Kitty as Kitty;
use Seeder;

class KittySeeder extends Seeder
{
    public function run()
    {

        $kitty = Kitty::create([
            'transaction_id'                 => '1',
            'credit_id'                 => '1',
            'spent'                 => 0,          
        ]);
    }
}