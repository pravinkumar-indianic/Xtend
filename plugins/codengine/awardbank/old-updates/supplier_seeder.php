<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Supplier as Supplier;
use Codengine\Awardbank\Models\Address as Address;
use Codengine\Awardbank\Models\Permission as Permission;
use Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {

        $supplier = Supplier::create([
            'name'                 => 'JB HiFi',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 25,
            'mark_up_type' => 'Percent',
            'primary_contact_name' => 'Johnny Blaze',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'johnny@fantasticfour.com.au',
            'secondary_contact_name' => 'Suzan Storm',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'suzan@fantasticfour.com.au',           
            
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(5);    
        $supplier->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $supplier->viewability()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $supplier->viewability()->attach($permission->id);

        $supplier2 = Supplier::create([
            'name'                 => 'Harvey Norman',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 20,
            'mark_up_type' => 'Percent',            
            'primary_contact_name' => 'Ben Grimm',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'ben@fantasticfour.com.au',
            'secondary_contact_name' => 'Victor Von Doom',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'victor@doom.com.au',                 
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(44);    
        $supplier2->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $supplier2->viewability()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $supplier2->viewability()->attach($permission->id);
        
         $supplier3 = Supplier::create([
            'name'                 => 'Kogan',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 20,
            'mark_up_type' => 'Percent',            
            'primary_contact_name' => 'Jessica Jones',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'jessica@defenders.com.au',
            'secondary_contact_name' => 'Luke Cage',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'luke@defenders.com.au',                  
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(37);    
        $supplier3->owners()->attach($permission->id);

        $supplier4 = Supplier::create([
            'name'                 => 'Accor Hotels',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 20,
            'mark_up_type' => 'Percent',            
            'primary_contact_name' => 'Abraham Sapien',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'abe@bprd.com.au',
            'secondary_contact_name' => 'Elizabeth Sherman',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'liz@defenders.com.au',                  
        ]);    
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(12);    
        $supplier4->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $supplier4->viewability()->attach($permission->id);

       $supplier5 = Supplier::create([
            'name'                 => 'The Good Guys',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 100,
            'mark_up_type' => 'Dollars',            
            'primary_contact_name' => 'Billy Blaze',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'captainmarvel@shazam.com.au',
            'secondary_contact_name' => 'Black Adam',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'blackadam@shazam.com.au',           
            
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(2);    
        $supplier5->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $supplier5->viewability()->attach($permission->id);

        $supplier6 = Supplier::create([
            'name'                 => 'John R. Turks',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 15,
            'mark_up_type' => 'Percent',                
            'primary_contact_name' => 'Harley Quinn',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'harley@joker.com.au',
            'secondary_contact_name' => 'Harvey Dent',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'harvey@twoface.com.au',                 
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(22);    
        $supplier6->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->regions()->attach(2);    
        $supplier6->viewability()->attach($permission->id);


         $supplier7 = Supplier::create([
            'name'                 => 'Spotify',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 10,
            'mark_up_type' => 'Dollars',              
            'primary_contact_name' => 'Butch Galzeen',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'butch@gotham.com.au',
            'secondary_contact_name' => 'Rick Grimes',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'rich@walking.com.au',                  
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(2);    
        $supplier7->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->organizations()->attach(1);    
        $supplier7->viewability()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->organizations()->attach(2);    
        $supplier7->viewability()->attach($permission->id);

        $supplier8 = Supplier::create([
            'name'                 => 'Ardent Leasure',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 25,
            'mark_up_type' => 'Percent',              
            'primary_contact_name' => 'Jesse Custer',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'jesse@preacher.com.au',
            'secondary_contact_name' => 'Cassidy Irish',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'cassidy@preacher.com.au',                  
        ]);    
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(12);    
        $supplier8->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $supplier8->viewability()->attach($permission->id);

        $supplier9 = Supplier::create([
            'name'                 => 'Appliance Warehouse',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 25,
            'mark_up_type' => 'Percent',              
            'primary_contact_name' => 'Carl Grimes',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'carl@walking.com.au',
            'secondary_contact_name' => 'Glenn Rhee',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => 'maggie@walking.com.au',                  
        ]);    
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(3);    
        $supplier9->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->organizations()->attach(2);    
        $supplier9->viewability()->attach($permission->id);

        $supplier10 = Supplier::create([
            'name'                 => 'Betta Electrical',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'mark_up_integer' => 25,
            'mark_up_type' => 'Percent',              
            'primary_contact_name' => 'Daryl Dixon',            
            'primary_contact_number' => '0000-000-000',
            'primary_contact_email' => 'daryl@walking.com.au',
            'secondary_contact_name' => '',                
            'secondary_contact_number' => '0000-000-000',
            'secondary_contact_email' => '',                  
        ]);    
    }
}