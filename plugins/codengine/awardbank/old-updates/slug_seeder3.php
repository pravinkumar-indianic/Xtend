<?php namespace Codengine\Awardbank\Updates;

use RainLab\User\Models\User;
use Seeder;
use Storage;
use Log;

class SlugSeeder3 extends Seeder
{
    public function run()
    {

        $users = User::all();
        foreach ($users as $user) {
            //$user->slug = null;
            if ($user->slug == null){
	            $user->slug = str_slug($user->full_name.' '.time().uniqid());
	            $user->save();
	            Log::info($user->slug);
	        }
        }
                         
    }
}