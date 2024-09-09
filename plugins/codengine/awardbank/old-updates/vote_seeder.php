<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Vote as Vote;
use Seeder;

class VoteSeeder extends Seeder
{
    public function run()
    {

        $vote = Vote::create([
            'nomination_id'                 => '1',
            'voter_id'                 => '1',
        ]);
    }
}