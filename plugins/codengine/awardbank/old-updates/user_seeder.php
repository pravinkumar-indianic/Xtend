<?php namespace Codengine\Awardbank\Updates;

use RainLab\User\Models\User as User;
use Codengine\Awardbank\Models\Team as Teams;
use Seeder;
use Storage;
use \System\Models\File;

class UserSeeder extends Seeder
{
    public function run()
    {

        $user = User::create([    
            'name'                 => 'Arthur',
            'surname'                 => 'Curry',
            'email'                => 'testuser1@test.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user2 = User::create([
            'name'                 => 'Peter',
            'surname'                 => 'Parker',            
            'email'                => 'spider@man.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);

        $user3 = User::create([
            'name'                 => 'Jean',
            'surname'                 => 'Paul Valley',            
            'email'                => 'azrael@bat.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);

        $user4 = User::create([
            'name'                 => 'Matt',
            'surname'                 => 'Murdock',            
            'email'                => 'dare@devil.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);  

        $user5 = User::create([    
            'name'                 => 'Barbara',
            'surname'                 => 'Gordon',
            'email'                => 'bat@woman.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user6 = User::create([
            'name'                 => 'Hank',
            'surname'                 => 'McCoy',            
            'email'                => 'beast@xman.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
         $user7 = User::create([
            'name'                 => 'Bruce',
            'surname'                 => 'Wayne',            
            'email'                => 'bruce@waynetech.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user8 = User::create([
            'name'                 => 'Steve',
            'surname'                 => 'Rogers',            
            'email'                => 'capn@merica.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);  

        $user9 = User::create([    
            'name'                 => 'Victor',
            'surname'                 => 'Stone',
            'email'                => 'cyborg@robot.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user10 = User::create([
            'name'                 => 'Boston',
            'surname'                 => 'Brand',            
            'email'                => 'dead@man.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user11 = User::create([
            'name'                 => 'Jason',
            'surname'                 => 'Blood',            
            'email'                => 'etrigan@demon.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
 


        $user12 = User::create([
            'name'                 => 'Oliver',
            'surname'                 => 'Queen',            
            'email'                => 'green@arrow.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);     

        $user13 = User::create([    
            'name'                 => 'Carter',
            'surname'                 => 'Hall',
            'email'                => 'hawk@man.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user14 = User::create([
            'name'                 => 'Shiera',
            'surname'                 => 'Saunders',            
            'email'                => 'hawk@girl.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user15 = User::create([
            'name'                 => 'Bobby',
            'surname'                 => 'Drake',            
            'email'                => 'ice@man.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user16 = User::create([
            'name'                 => 'Cain',
            'surname'                 => 'Marko',            
            'email'                => 'juggernaught@smash.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);   

        $user17 = User::create([    
            'name'                 => 'Wally',
            'surname'                 => 'West',
            'email'                => 'flash@speedster.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user18 = User::create([
            'name'                 => 'Bruce',
            'surname'                 => 'Banner',            
            'email'                => 'hulk@smash.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user19 = User::create([
            'name'                 => 'Jonn',
            'surname'                 => 'Jonzz',            
            'email'                => 'martian@manhunter.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user20 = User::create([
            'name'                 => 'Kitty',
            'surname'                 => 'Pride',            
            'email'                => 'kitty@xman.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);  

        $user21 = User::create([
            'name'                 => 'Robert',
            'surname'                 => 'Langstrom',            
            'email'                => 'man@bat.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user22 = User::create([
            'name'                 => 'Reed',
            'surname'                 => 'Richards',            
            'email'                => 'reed@fantastic.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);     

        $user23 = User::create([    
            'name'                 => 'Kurt',
            'surname'                 => 'Wagner',
            'email'                => 'nightcrawler@xman.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user24 = User::create([
            'name'                 => 'Jason',
            'surname'                 => 'Todd',            
            'email'                => 'red@hood.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user25 = User::create([
            'name'                 => 'Edward',
            'surname'                 => 'Nygma',            
            'email'                => 'riddle@riddler.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user26 = User::create([
            'name'                 => 'Clark',
            'surname'                 => 'Kent',            
            'email'                => 'kansas@power.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);   

        $user27 = User::create([    
            'name'                 => 'Barry',
            'surname'                 => 'Allen',
            'email'                => 'flash2@speedster.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user28 = User::create([
            'name'                 => 'James',
            'surname'                 => 'Howlett',            
            'email'                => 'wolverine@xman.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user29 = User::create([
            'name'                 => 'Jack',
            'surname'                 => 'Napier',            
            'email'                => 'joker@maybe.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user30 = User::create([
            'name'                 => 'Ororo',
            'surname'                 => 'Monroe',            
            'email'                => 'storm@xman.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]); 

        $user31 = User::create([
            'name'                 => 'Nick',
            'surname'                 => 'Fury',            
            'email'                => 'nick@shield.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user32 = User::create([
            'name'                 => 'Steven',
            'surname'                 => 'Strange',            
            'email'                => 'dr@agomoto.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);     

        $user33 = User::create([    
            'name'                 => 'Ben',
            'surname'                 => 'Reilly',
            'email'                => 'scarlet@spider.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user34 = User::create([
            'name'                 => 'Felicia',
            'surname'                 => 'Hardy',            
            'email'                => 'black@cat.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user35 = User::create([
            'name'                 => 'TChalla',
            'surname'                 => 'Udaku',            
            'email'                => 'black@panther.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user36 = User::create([
            'name'                 => 'Fred',
            'surname'                 => 'Myers',            
            'email'                => 'capn@boomerang.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);   

        $user37 = User::create([    
            'name'                 => 'Brian',
            'surname'                 => 'Braddock',
            'email'                => 'capn@britan.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user38 = User::create([
            'name'                 => 'John',
            'surname'                 => 'Constantine',            
            'email'                => 'hell@blazer.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user39 = User::create([
            'name'                 => 'Gwen',
            'surname'                 => 'Stacy',            
            'email'                => 'spider@gwen.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user40 = User::create([
            'name'                 => 'Wade',
            'surname'                 => 'Wilson',            
            'email'                => 'dead@pool.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);                                                    

        $user41 = User::create([
            'name'                 => 'Otto',
            'surname'                 => 'Octavius',            
            'email'                => 'dr@oc.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user42 = User::create([
            'name'                 => 'Elektra',
            'surname'                 => 'Natchios',            
            'email'                => 'elektra@thehand.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);     

        $user43 = User::create([    
            'name'                 => 'Sam',
            'surname'                 => 'Wilson',
            'email'                => 'falcon@america.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user44 = User::create([
            'name'                 => 'Remy',
            'surname'                 => 'LeBeau',            
            'email'                => 'gambit@xmen.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user45 = User::create([
            'name'                 => 'Hank',
            'surname'                 => 'Pym',            
            'email'                => 'ant@man.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user46 = User::create([
            'name'                 => 'Scott',
            'surname'                 => 'Lang',            
            'email'                => 'ant2@man.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);   

        $user47 = User::create([    
            'name'                 => 'Tony',
            'surname'                 => 'Stark',
            'email'                => 'iron@man.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',            
            'is_activated'         => 1,     
            
        ]);

        $user48 = User::create([
            'name'                 => 'Clint',
            'surname'                 => 'Barton',            
            'email'                => 'hawk@eye.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user49 = User::create([
            'name'                 => 'Wilson',
            'surname'                 => 'Fisk',            
            'email'                => 'king@pin.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);
        
        $user50 = User::create([
            'name'                 => 'Frank',
            'surname'                 => 'Castle',            
            'email'                => 'punish@er.com.au',
            'password'             => 'test',
            'password_confirmation' => 'test',
            'is_activated'         => 1,               
        ]);   

        foreach(User::all() as $loop){
            $loop->full_name = $loop->name.' '.$loop->surname;
            $loop->save();
        }  

        $i = 1;

        foreach(User::all() as $loop){
            $team = Teams::find($i);
            $team->users()->attach($loop->id);
            if($i == 24){
                $i = 1;
            } else {
                $i++;
            }
            $feature_image = Storage::disk('s3')->get('/media/profile/'.$loop->id.'.jpg');
            $file = new File;
            $file->fromData($feature_image, 'header.png');
            $file->save();
            $loop->avatar()->add($file);            
        }   

        $team = Teams::find(12);
        $team->users()->attach(1);
        $team = Teams::find(24);
        $team->users()->attach(1);                              
    }
}