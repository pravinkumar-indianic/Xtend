<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Organization as Organization;
use Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $organization = Organization::create([
            'name'                 => 'Peppers',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',   
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]);        

        $organization2 = Organization::create([
            'name'                 => 'Toyota',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',      
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]); 
    }
}