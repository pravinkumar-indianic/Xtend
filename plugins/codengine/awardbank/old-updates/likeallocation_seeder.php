<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\LikeAllocation as LikeAllocation;
use Seeder;

class LikeAllocationSeeder extends Seeder
{
    public function run()
    {

        $like1 = LikeAllocation::create([
            'like_id' 			=> '1',
            'likeable_type'		=> 'Codengine\Awardbank\Models\Like',
            'likeable_id'		=> '1'
        ]);

        $like2 = LikeAllocation::create([
            'like_id' 			=> '2',
            'likeable_type'		=> 'Codengine\Awardbank\Models\Like',
            'likeable_id'		=> '1'
        ]);

    }
}