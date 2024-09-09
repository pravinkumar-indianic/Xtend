<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Tag as Tag;
use Seeder;

class TagSeeder extends Seeder
{
    public function run()
    {


        $Tag1 = Tag::create([
            'name' => 'Gadgets',
        ]);

        $Tag2 = Tag::create([
            'name' => 'Summer Sport',
        ]);
        
        $Tag3 = Tag::create([
            'name' => 'Outdoors',
        ]);

        $Tag4 = Tag::create([
            'name' => 'Winter',
        ]); 

        $Tag5 = Tag::create([
            'name' => 'Christmas',
        ]); 

        $Tag6 = Tag::create([
            'name' => 'Easter',
        ]); 

        $Tag7 = Tag::create([
            'name' => 'NYE',
        ]); 

        $Tag8 = Tag::create([
            'name' => 'Team Building',
        ]); 

    }
}