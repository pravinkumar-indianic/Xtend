<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Address as Address;
use Codengine\Awardbank\Models\Program as Program;
use Seeder;
use Storage;
use Config;
use \System\Models\File;

class ProgramSeeder extends Seeder
{
    public function run()
    {

        //var_dump(Storage::disk('s3')->exists('xtend.png'));

        $program = Program::create([
            'name'                 => 'Staff Rewards',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',   
             'organization_id' => 1,
             'primary_color' => '#3498db',
             'secondary_color' => '#f1c40f',
        ]);        

        $feature_image = Storage::disk('s3')->get('/media/program/staff_rewards_feature.png');
        $file = new File;
        $file->fromData($feature_image, 'header.png');
        $file->save();
        $program->feature_image()->add($file);

        $feature_image = Storage::disk('s3')->get('/media/program/staff_rewards_header_icon.png');
        $file = new File;
        $file->fromData($feature_image, 'header.png');
        $file->save();
        $program->header_icon()->add($file);

        $feature_image = Storage::disk('s3')->get('/media/program/staff_rewards_login_image.png');
        $file = new File;
        $file->fromData($feature_image, 'header.png');
        $file->save();
        $program->login_image()->add($file);

        $program2 = Program::create([
            'name'                 => 'Millionaires',
             'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',        
             'organization_id' => 2,
             'primary_color' => '#d35400',
             'secondary_color' => '#c0392b',             
        ]); 

        $feature_image = Storage::disk('s3')->get('/media/program/millionaires_feature.png');
        $file = new File;
        $file->fromData($feature_image, 'header.png');
        $file->save();
        $program2->feature_image()->add($file);

        $feature_image = Storage::disk('s3')->get('/media/program/millionaires_header_icon.png');
        $file = new File;
        $file->fromData($feature_image, 'header.png');
        $file->save();  
        $program2->header_icon()->add($file);

        $feature_image = Storage::disk('s3')->get('/media/program/millionaires_login_image.png');
        $file = new File;
        $file->fromData($feature_image, 'header.png');
        $file->save();
        $program2->login_image()->add($file);

    }
}