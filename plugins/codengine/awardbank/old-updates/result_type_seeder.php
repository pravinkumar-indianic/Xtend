<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\ResultType as ResultType;
use Seeder;

class ResultTypeSeeder extends Seeder
{
    public function run()
    {

        $resultType = ResultType::create([
            'type'                 => '1',
            'benchmark'                 => 100,
            'allocated_points_value'                 => '2',          
        ]);
    }
}