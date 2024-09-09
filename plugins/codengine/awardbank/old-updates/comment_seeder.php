<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Comment as Comment;
use Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {

        $Comment1 = Comment::create([
            'comment'  => 'test comment 1',
            'user_id'	=> '1'
        ]);

        $Comment2 = Comment::create([
            'comment'  => 'test comment 2',
            'user_id'	=> '2'
        ]);

    }
}