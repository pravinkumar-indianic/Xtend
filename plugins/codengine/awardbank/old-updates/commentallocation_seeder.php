<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\CommentAllocation as CommentAllocation;
use Seeder;

class CommentAllocationSeeder extends Seeder
{
    public function run()
    {

        $coomment1 = CommentAllocation::create([
            'comment_id' 			=> '1',
            'commantable_type'		=> 'Codengine\Awardbank\Models\Post',
            'commantable_id'		=> '1'
        ]);

        $coomment2 = CommentAllocation::create([
            'comment_id' 			=> '2',
            'commantable_type'		=> 'Codengine\Awardbank\Models\Post',
            'commantable_id'		=> '1'
        ]);

    }
}