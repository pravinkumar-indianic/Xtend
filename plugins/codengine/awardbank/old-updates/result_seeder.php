<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Result as Result;
use Seeder;

class ResultSeeder extends Seeder
{
    public function run()
    {

        $result = Result::create([
            'value'                 => '1',
            'resulttype_id'                 => '1',
            'label' => 'required',
        ]);
    }
}