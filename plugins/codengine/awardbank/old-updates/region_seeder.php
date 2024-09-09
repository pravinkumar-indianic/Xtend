<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Region as Region;
use Seeder;

class RegionSeeder extends Seeder
{
    public function run()
    {
        $region = Region::create([
            'name'                 => 'West Coast',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',   
             'program_id' => 1,
              'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]);   

        $region->teams()->attach(4);              

        $region2 = Region::create([
            'name'                 => 'East Coast',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',        
             'program_id' => 1,
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]); 
        $region2->teams()->attach(1);   
        $region2->teams()->attach(2); 
        $region2->teams()->attach(3);                 

        $region3 = Region::create([
            'name'                 => 'ERO',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',   
             'program_id' => 2,
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]);   
        $region3->teams()->attach(5);              
        $region3->teams()->attach(6);  
        $region3->teams()->attach(7);  
        $region3->teams()->attach(8);                  


        $region4 = Region::create([
            'name'                 => 'CRO',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',        
             'program_id' => 2,
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]); 
        $region4->teams()->attach(9);              
        $region4->teams()->attach(10);  
        $region4->teams()->attach(11);  
        $region4->teams()->attach(12);   
 
        $region5 = Region::create([
            'name'                 => 'NRO',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',   
             'program_id' => 2,
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]);   

        $region5->teams()->attach(13);              
        $region5->teams()->attach(14);  
        $region5->teams()->attach(15);  
        $region5->teams()->attach(16);          

        $region6 = Region::create([
            'name'                 => 'SRO',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',        
             'program_id' => 2,
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]); 
        $region6->teams()->attach(17);              
        $region6->teams()->attach(18);  
        $region6->teams()->attach(19);  
        $region6->teams()->attach(20);  

        $region7 = Region::create([
            'name'                 => 'WA',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',        
             'program_id' => 2,
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]); 
        $region7->teams()->attach(21);              
        $region7->teams()->attach(22);  
        $region7->teams()->attach(23);  
        $region7->teams()->attach(24);                           
    }
}