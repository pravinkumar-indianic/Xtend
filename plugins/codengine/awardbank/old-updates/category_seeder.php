<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Category as category;
use Codengine\Awardbank\Models\Permission;
use Seeder;

class categorySeeder extends Seeder
{
    public function run()
    {

/** 
    TECHNOLOGY TREE
**/

        $technology = category::create([
            'name'                 => 'Technology',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($technology->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    TECHNOLOGY TREE BRANCH 1 
**/

        $productcategory1 = $technology->children()->create([
            'name'                 => 'TV, Blu-ray & Home Theatre',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_1 =  $productcategory1->children()->create([
            'name'                 => 'TV',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory1_1_new1 =  $productcategory1_1->children()->create([
            'name'                 => '4k Ultra HD TVs',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_new1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_1_new2 =  $productcategory1_1->children()->create([
            'name'                 => 'Full HD TVs',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_new2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_1_new3 =  $productcategory1_1->children()->create([
            'name'                 => 'OLED TVs',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_new3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_1_new4 =  $productcategory1_1->children()->create([
            'name'                 => 'Smart TVs',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_new4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_1_1 =  $productcategory1_1->children()->create([
            'name'                 => '24"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_1_2 =  $productcategory1_1->children()->create([
            'name'                 => '32"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_1_3 =  $productcategory1_1->children()->create([
            'name'                 => '40"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_1_4 =  $productcategory1_1->children()->create([
            'name'                 => '43"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_1_5 =  $productcategory1_1->children()->create([
            'name'                 => '49"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_1_6 =  $productcategory1_1->children()->create([
            'name'                 => '50"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_1_7 =  $productcategory1_1->children()->create([
            'name'                 => '55"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);


        $productcategory1_1_8 =  $productcategory1_1->children()->create([
            'name'                 => '60"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_1_9 =  $productcategory1_1->children()->create([
            'name'                 => '65"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_9->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_2 =  $productcategory1->children()->create([
            'name'                 => 'Blu-Ray & DVD',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_2_1 =  $productcategory1_2->children()->create([
            'name'                 => 'Blu-ray Players & Recorders',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_2_2 =  $productcategory1_2->children()->create([
            'name'                 => 'DVD Players & Recorders',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_2_3 =  $productcategory1_2->children()->create([
            'name'                 => 'PVRs & Media Players',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_3 =  $productcategory1->children()->create([
            'name'                 => 'Home Theatre',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_3_1 =  $productcategory1_3->children()->create([
            'name'                 => 'Home Theatre & Speakers (incl. Soundbars)',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_3_2 =  $productcategory1_3->children()->create([
            'name'                 => 'AV Receivers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1_3_3 =  $productcategory1_3->children()->create([
            'name'                 => 'Projectors',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_3_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_4 =  $productcategory1->children()->create([
            'name'                 => 'Connected Home',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  


/** 
    TECHNOLOGY TREE BRANCH 2
**/

        $productcategory2 = $technology->children()->create([
            'name'                 => 'Headphones, Audio & Music',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_1 =  $productcategory2->children()->create([
            'name'                 => 'Headphones',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_1_1 =  $productcategory2_1->children()->create([
            'name'                 => 'In-Ear',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_1_2 =  $productcategory2_1->children()->create([
            'name'                 => 'On/Over Ear',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_1_3 =  $productcategory2_1->children()->create([
            'name'                 => 'Wireless',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_2 =  $productcategory2->children()->create([
            'name'                 => 'Audio',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_2_1 =  $productcategory2_2->children()->create([
            'name'                 => 'Speakers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_2_2 =  $productcategory2_2->children()->create([
            'name'                 => 'Portable Speakers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_2_3 =  $productcategory2_2->children()->create([
            'name'                 => 'Mini & Micro Hi-Fi',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_2_4 =  $productcategory2_2->children()->create([
            'name'                 => 'Turntables',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_2_5 =  $productcategory2_2->children()->create([
            'name'                 => 'Radios',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_2_6 =  $productcategory2_2->children()->create([
            'name'                 => 'Audio Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_3 =  $productcategory2->children()->create([
            'name'                 => 'Music',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_3_1 =  $productcategory2_3->children()->create([
            'name'                 => 'iPods & Portable Music Players',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory2_3_2 =  $productcategory2_3->children()->create([
            'name'                 => 'Musical Instruments',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    TECHNOLOGY TREE BRANCH 3
**/

        $productcategory3 = $technology->children()->create([
            'name'                 => 'Computers & Tablets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3->id); 
        $permission->programs()->attach(1);         

        $productcategory3_1 =  $productcategory3->children()->create([
            'name'                 => 'Computers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1->id); 
        $permission->programs()->attach(1);         

        $productcategory3_1_1 =  $productcategory3_1->children()->create([
            'name'                 => 'Laptops',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1_1->id); 
        $permission->programs()->attach(1);         

        $productcategory3_1_2 =  $productcategory3_1->children()->create([
            'name'                 => 'PCs',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1_2->id); 
        $permission->programs()->attach(1);         

        $productcategory3_1_3 =  $productcategory3_1->children()->create([
            'name'                 => 'Monitors',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1_3->id); 
        $permission->programs()->attach(1);    

        $productcategory3_1_4 =  $productcategory3_1->children()->create([
            'name'                 => 'Keyboards & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1_4->id); 
        $permission->programs()->attach(1);  

        $productcategory3_1_5 =  $productcategory3_1->children()->create([
            'name' => 'Cases & Laptop Bags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1_5->id); 
        $permission->programs()->attach(1); 

        $productcategory3_1_6 =  $productcategory3_1->children()->create([
            'name' => 'Storage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1_6->id); 
        $permission->programs()->attach(1); 

        $productcategory3_1_7 =  $productcategory3_1->children()->create([
            'name' => 'Printers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_1_7->id); 
        $permission->programs()->attach(1); 

        $productcategory3_2 =  $productcategory3->children()->create([
            'name'                 => 'Tablets & Cases',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory3_2_1 =  $productcategory3_2->children()->create([
            'name' => 'Apple iPads',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_2_1->id); 
        $permission->programs()->attach(1); 

       $productcategory3_2_2 =  $productcategory3_2->children()->create([
            'name' => 'Samsung & Android Tablets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_2_2->id); 
        $permission->programs()->attach(1); 

       $productcategory3_2_3 =  $productcategory3_2->children()->create([
            'name' => 'Cases & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory3_2_3->id); 
        $permission->programs()->attach(1); 

/** 
    TECHNOLOGY TREE BRANCH 4 
**/

        $productcategory4 = $technology->children()->create([
            'name'                 => 'Phones',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory4->id);       
        $permission->programs()->attach(2); 

       $productcategory4_2 =  $productcategory4->children()->create([
            'name' => 'Mobile Phones',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory4_2->id); 
        $permission->programs()->attach(2); 

        $productcategory4_1 =  $productcategory4->children()->create([
            'name'                 => 'DECT Phones',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory4_1->id); 
        $permission->programs()->attach(2);         

       $productcategory4_3 =  $productcategory4->children()->create([
            'name' => 'Cases & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory4_3->id); 
        $permission->programs()->attach(2); 

        $wearables = $technology->children()->create([
            'name'                 => 'Wearables & GPS',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory4->id);       
        $permission->programs()->attach(2); 

        $productcategoryw_2 =  $wearables->children()->create([
            'name'                 => 'Wearables',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_2->id); 
        $permission->programs()->attach(2);  

       $productcategoryw_2_2 =  $productcategoryw_2->children()->create([
            'name' => 'Smart Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_2_2->id); 
        $permission->programs()->attach(2); 

       $productcategoryw_2_1 =  $productcategoryw_2->children()->create([
            'name' => 'Connected Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_2_1->id); 
        $permission->programs()->attach(2); 

       $productcategoryw_2_3 =  $productcategoryw_2->children()->create([
            'name' => 'Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_2_3->id); 
        $permission->programs()->attach(2); 

       $productcategoryw_2_4 =  $productcategoryw_2->children()->create([
            'name' => 'Fitness',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_2_4->id); 
        $permission->programs()->attach(2); 

        $productcategoryw_3 =  $wearables->children()->create([
            'name'                 => 'GPS',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_3->id); 
        $permission->programs()->attach(2);  

       $productcategoryw_3_1 =  $productcategoryw_3->children()->create([
            'name' => 'In-Car GPS',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_3_1->id); 
        $permission->programs()->attach(2); 

       $productcategoryw_3_2 =  $productcategoryw_3->children()->create([
            'name' => 'Hand-held GPS',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_3_2->id); 
        $permission->programs()->attach(2);

       $productcategoryw_3_3 =  $productcategoryw_3->children()->create([
            'name' => 'Dash Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_3_3->id); 
        $permission->programs()->attach(2);

       $productcategoryw_3_4 =  $productcategoryw_3->children()->create([
            'name' => 'Bike Computers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_3_4->id); 
        $permission->programs()->attach(2);

       $productcategoryw_3_5 =  $productcategoryw_3->children()->create([
            'name' => 'GPS Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryw_3_5->id); 
        $permission->programs()->attach(2);

/** 
    TECHNOLOGY TREE BRANCH 5
**/

        $productcategory5 = $technology->children()->create([
            'name'                 => 'Gaming & Tech',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5->id);       
        $permission->programs()->attach(2); 

        $productcategory5_1 =  $productcategory5->children()->create([
            'name'                 => 'Xbox',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_1->id); 
        $permission->programs()->attach(2);         

       $productcategory5_1_1 =  $productcategory5_1->children()->create([
            'name' => 'Consoles & Bundles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_1_1->id); 
        $permission->programs()->attach(2); 

       $productcategory5_1_2 =  $productcategory5_1->children()->create([
            'name' => 'Xbox One Games',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_1_2->id); 
        $permission->programs()->attach(2); 

       $productcategory5_1_3 =  $productcategory5_1->children()->create([
            'name' => 'Xbox One Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_1_3->id); 
        $permission->programs()->attach(2); 

       $productcategory5_2 =  $productcategory5->children()->create([
            'name'                 => 'Playstation',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_2->id); 
        $permission->programs()->attach(2);         

       $productcategory5_2_1 =  $productcategory5_2->children()->create([
            'name' => 'Consoles & Bundles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_2_1->id); 
        $permission->programs()->attach(2); 

       $productcategory5_2_2 =  $productcategory5_2->children()->create([
            'name' => 'Playstation Games',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_2_2->id); 
        $permission->programs()->attach(2); 

       $productcategory5_2_3 =  $productcategory5_2->children()->create([
            'name' => 'Playstation Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_2_3->id); 
        $permission->programs()->attach(2); 

       $productcategory5_3 =  $productcategory5->children()->create([
            'name'                 => 'Nintendo',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_3->id); 
        $permission->programs()->attach(2);         

       $productcategory5_3_1 =  $productcategory5_3->children()->create([
            'name' => '3Ds',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_3_1->id); 
        $permission->programs()->attach(2);

       $productcategory5_3_2 =  $productcategory5_3->children()->create([
            'name' => 'Switch',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_3_2->id); 
        $permission->programs()->attach(2);

       $productcategory5_3_3 =  $productcategory5_3->children()->create([
            'name' => 'Games & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_3_3->id); 
        $permission->programs()->attach(2);

      $productcategory5_4 =  $productcategory5->children()->create([
            'name'                 => 'PC Gaming',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_4->id); 
        $permission->programs()->attach(2);         

       $productcategory5_4_1 =  $productcategory5_4->children()->create([
            'name' => 'PC Games',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_4_1->id); 
        $permission->programs()->attach(2);

       $productcategory5_4_2 =  $productcategory5_4->children()->create([
            'name' => 'PC Gaming Headsets & Keyboards',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_4_2->id); 
        $permission->programs()->attach(2);

      $productcategory5_5 =  $productcategory5->children()->create([
            'name'                 => 'Tech Toys',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_5->id); 
        $permission->programs()->attach(2);         

       $productcategory5_5_1 =  $productcategory5_5->children()->create([
            'name' => 'VR & Augmented Reality',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_5_1->id); 
        $permission->programs()->attach(2);

       $productcategory5_5_2 =  $productcategory5_5->children()->create([
            'name' => 'Drones',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_5_2->id); 
        $permission->programs()->attach(2);

       $productcategory5_5_3 =  $productcategory5_5->children()->create([
            'name' => 'RC Vehicles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_5_3->id); 
        $permission->programs()->attach(2);

       $productcategory5_5_4 =  $productcategory5_5->children()->create([
            'name' => 'Pop!Vinyl & Collectibles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory5_5_4->id); 
        $permission->programs()->attach(2);

/**     
    TECHNOLOGY TREE BRANCH 6 
**/

        $productcategory6 = $technology->children()->create([
            'name'                 => 'Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6->id);       
        $permission->programs()->attach(1); 

        $productcategory6_1 =  $productcategory6->children()->create([
            'name'                 => 'Digital Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1->id); 
        $permission->programs()->attach(1);         

       $productcategory6_1_1 =  $productcategory6_1->children()->create([
            'name' => 'Compact Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1_1->id); 
        $permission->programs()->attach(1); 

        $productcategory6_1_2 =  $productcategory6_1->children()->create([
            'name' => 'DSLR / High Performance Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1_2->id); 
        $permission->programs()->attach(1); 

        $productcategory6_1_3 =  $productcategory6_1->children()->create([
            'name' => 'Instant Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1_3->id); 
        $permission->programs()->attach(1); 

        $productcategory6_1_4 =  $productcategory6_1->children()->create([
            'name' => 'Cases & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1_4->id); 
        $permission->programs()->attach(1); 

        $productcategory6_1_5 =  $productcategory6_1->children()->create([
            'name' => 'Storage & Memory',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1_5->id); 
        $permission->programs()->attach(1); 

        $productcategory6_1_6 =  $productcategory6_1->children()->create([
            'name' => 'Printers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1_6->id); 
        $permission->programs()->attach(1); 

       $productcategory6_1_7 =  $productcategory6_1->children()->create([
            'name' => 'Camera Lens',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_1_7->id); 
        $permission->programs()->attach(1); 

        $productcategory6_2 =  $productcategory6->children()->create([
            'name'                 => 'Action Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_2->id); 
        $permission->programs()->attach(1);         

       $productcategory6_2_1 =  $productcategory6_2->children()->create([
            'name' => 'GoPro & Action Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_2_1->id); 
        $permission->programs()->attach(1); 

        $productcategory6_2_2 =  $productcategory6_2->children()->create([
            'name' => 'Video Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_2_2->id); 
        $permission->programs()->attach(1); 

        $productcategory6_2_3 =  $productcategory6_2->children()->create([
            'name' => 'Cases & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_2_3->id); 
        $permission->programs()->attach(1); 

       $productcategory6_3 =  $productcategory6->children()->create([
            'name'                 => 'Security',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_3->id); 
        $permission->programs()->attach(1);         

       $productcategory6_3_1 =  $productcategory6_3->children()->create([
            'name' => 'Security Cameras & Monitors',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_3_1->id); 
        $permission->programs()->attach(1); 

       $productcategory6_3_2 =  $productcategory6_3->children()->create([
            'name' => 'Dash Cameras',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory6_3_2->id); 
        $permission->programs()->attach(1); 

/** 
    OUTDOOR & LIFESTLE TREE
**/

        $outdoor = category::create([
            'name'                 => 'Outdoor & Lifestyle',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($outdoor->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    OUTDOOR TREE BRANCH 1 
**/

        $productcategory7 = $outdoor->children()->create([
            'name'                 => 'Sport & Fitness',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory7_1 =  $productcategory7->children()->create([
            'name'                 => 'Sport',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory7_1_1 =  $productcategory7_1->children()->create([
            'name'                 => 'Golf',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory7_1_2 =  $productcategory7_1->children()->create([
            'name'                 => 'Tennis',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory7_1_3 =  $productcategory7_1->children()->create([
            'name'                 => 'Balls & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory7_1_4 =  $productcategory7_1->children()->create([
            'name'                 => 'Outdoor Games',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory7_2 =  $productcategory7->children()->create([
            'name'                 => 'Gym',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory7_2_1 =  $productcategory7_2->children()->create([
            'name'                 => 'Excercise Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory7_2_2=  $productcategory7_2->children()->create([
            'name'                 => 'Home Gym',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory7_2_3 =  $productcategory7_2->children()->create([
            'name'                 => 'Elipticals',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory7_2_4 =  $productcategory7_2->children()->create([
            'name'                 => 'Rowers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_2_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory7_2_5 =  $productcategory7_2->children()->create([
            'name'                 => 'Treadmills',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_2_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory7_2_6 =  $productcategory7_2->children()->create([
            'name'                 => 'Strength Training & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_2_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory7_3 =  $productcategory7->children()->create([
            'name'                 => 'Fitness',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory7_3_1 =  $productcategory7_3->children()->create([
            'name'                 => 'Wearables',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory7_3_2 =  $productcategory7_3->children()->create([
            'name'                 => 'Fit Balls, Mats & Rollers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory7_3_3 =  $productcategory7_3->children()->create([
            'name'                 => 'Fitness Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory7_3_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    OUTDOOR TREE BRANCH 2
**/

        $productcategory8 = $outdoor->children()->create([
            'name'                 => 'Bikes & Scooters',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory8_1 =  $productcategory8->children()->create([
            'name'                 => 'Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory8_1_1 =  $productcategory8_1->children()->create([
            'name'                 => 'Mountain Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory8_1_2 =  $productcategory8_1->children()->create([
            'name'                 => 'Commuter Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory8_1_3 =  $productcategory8_1->children()->create([
            'name'                 => 'Road Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory8_1_4 =  $productcategory8_1->children()->create([
            'name'                 => 'Ladies Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory8_1_5 =  $productcategory8_1->children()->create([
            'name'                 => 'Electric & Foldable Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory8_1_6 =  $productcategory8_1->children()->create([
            'name'                 => 'Fat Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

       $productcategory8_1_7 =  $productcategory8_1->children()->create([
            'name'                 => 'Bike Computers & Accesories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory8_1_8 =  $productcategory8_1->children()->create([
            'name'                 => 'Kids Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_1_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory8_2 =  $productcategory8->children()->create([
            'name'                 => 'Scooters',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory8_2_1 =  $productcategory8_2->children()->create([
            'name'                 => 'Kids Scooters',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory8_2_2 =  $productcategory8_2->children()->create([
            'name'                 => 'Electric Scooters',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory8_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    OUTDOOR TREE BRANCH 3
**/

        $productcategory9 = $outdoor->children()->create([
            'name'                 => 'Beach & Fishing',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory9_1 =  $productcategory9->children()->create([
            'name'                 => 'Beach',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory9_1_1 =  $productcategory9_1->children()->create([
            'name'                 => 'Beach Chairs',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory9_1_2 =  $productcategory9_1->children()->create([
            'name'                 => 'Tents & Shelter',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  


        $productcategory9_1_4 =  $productcategory9_1->children()->create([
            'name'                 => 'Beach Towels',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory9_1_5 =  $productcategory9_1->children()->create([
            'name'                 => 'Outdoor Games',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory9_1_6 =  $productcategory9_1->children()->create([
            'name'                 => 'Boating & Inflatables',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory9_1_7 =  $productcategory9_1->children()->create([
            'name'                 => 'Coolers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory9_1_8 =  $productcategory9_1->children()->create([
            'name'                 => 'Wetsuits',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory9_1_9 =  $productcategory9_1->children()->create([
            'name'                 => 'Picnic',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_9->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory9_1_10 =  $productcategory9_1->children()->create([
            'name'                 => 'Beach Bags & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_1_10->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory9_2 =  $productcategory9->children()->create([
            'name'                 => 'Fishing',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory9_2_1 =  $productcategory9_2->children()->create([
            'name'                 => 'Reels',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

      $productcategory9_2_2 =  $productcategory9_2->children()->create([
            'name'                 => 'Combos',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory9_2_3 =  $productcategory9_2->children()->create([
            'name'                 => 'Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

       $productcategory9_2_4 =  $productcategory9_2->children()->create([
            'name'                 => 'Chest Coolers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory9_2_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

/** 
    FIX CAMPING + HIKING
**/

        $campingcategory = $outdoor->children()->create([
            'name'                 => 'Camping & Hiking',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $campingcategory_1 =  $campingcategory->children()->create([
            'name'                 => 'Camping',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_1_1 =  $campingcategory_1->children()->create([
            'name'                 => 'Tents & Shelter',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_1_2 =  $campingcategory_1->children()->create([
            'name'                 => 'Camp Furniture',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_1_3 =  $campingcategory_1->children()->create([
            'name'                 => 'Lighting',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_1_4 =  $campingcategory_1->children()->create([
            'name'                 => 'Camp Cooking',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_1_5 =  $campingcategory_1->children()->create([
            'name'                 => 'Coolers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_1_6 =  $campingcategory_1->children()->create([
            'name'                 => 'Air Beds',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_1_7 =  $campingcategory_1->children()->create([
            'name'                 => 'Sleeping Bags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $campingcategory_1_8 =  $campingcategory_1->children()->create([
            'name'                 => 'Camping Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_1_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $campingcategory_2 =  $campingcategory->children()->create([
            'name'                 => 'Telescopes & Binoculars',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $campingcategory_2_1 =  $campingcategory_2->children()->create([
            'name'                 => 'Telescopes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $campingcategory_2_2 =  $campingcategory_2->children()->create([
            'name'                 => 'Binoculars',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 


        $campingcategory_3 =  $campingcategory->children()->create([
            'name'                 => 'Hiking',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_3_1 =  $campingcategory_3->children()->create([
            'name'                 => 'GPS',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_3_2 =  $campingcategory_3->children()->create([
            'name'                 => 'Backpacks',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $campingcategory_3_3 =  $campingcategory_3->children()->create([
            'name'                 => 'Hiking Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($campingcategory_3_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    OUTDOOR TREE BRANCH 4
**/

        $productcategory10 = $outdoor->children()->create([
            'name'                 => 'BBQ & Entertaining',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory10_1 =  $productcategory10->children()->create([
            'name'                 => 'BBQ',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_1_1 =  $productcategory10_1->children()->create([
            'name'                 => 'Portable BBQ',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory10_1_2 =  $productcategory10_1->children()->create([
            'name'                 => 'Freestanding BBQ',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory10_1_3 =  $productcategory10_1->children()->create([
            'name'                 => 'In-built BBQ',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_1_new1 =  $productcategory10_1->children()->create([
            'name'                 => 'Smokers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_new1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_1_new2 =  $productcategory10_1->children()->create([
            'name'                 => 'Pizza Ovens',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_new2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_1_new3 =  $productcategory10_1->children()->create([
            'name'                 => 'Kettles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_new3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_1_new4 =  $productcategory10_1->children()->create([
            'name'                 => 'Outdoor Kitchen',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_new4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_1_4 =  $productcategory10_1->children()->create([
            'name'                 => 'BBQ Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory10_2 =  $productcategory10->children()->create([
            'name'                 => 'Outdoor Entertaining',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_2_1 =  $productcategory10_2->children()->create([
            'name'                 => 'Outdoor Furniture',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory10_2_2 =  $productcategory10_2->children()->create([
            'name'                 => 'Gazebos & Shade',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory10_2_3 =  $productcategory10_2->children()->create([
            'name'                 => 'Outdoor Heating',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory10_2_5 =  $productcategory10_2->children()->create([
            'name'                 => 'Picnic',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_2_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory10_3 =  $productcategory10->children()->create([
            'name'                 => 'Outdoor Play',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_3_1 =  $productcategory10_3->children()->create([
            'name'                 => 'Cubby Houses',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory10_3_2 =  $productcategory10_3->children()->create([
            'name'                 => 'Swing Sets & Slides',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_3_3 =  $productcategory10_3->children()->create([
            'name'                 => 'Sand Pits',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_3_4 =  $productcategory10_3->children()->create([
            'name'                 => 'Jumping Castles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_3_5 =  $productcategory10_3->children()->create([
            'name'                 => 'Pool Toys',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

      $productcategory10_3_6 =  $productcategory10_3->children()->create([
            'name'                 => 'Outdoor Games',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    OUTDOOR TREE BRANCH 5
**/

        $productcategory11 = $outdoor->children()->create([
            'name'                 => 'Garden & DIY',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory11_1 =  $productcategory11->children()->create([
            'name'                 => 'Garden',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory11_1_1 =  $productcategory11_1->children()->create([
            'name'                 => 'Lawn Mowers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory11_1_2 =  $productcategory11_1->children()->create([
            'name'                 => 'Garden Power Tools',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory11_1_3 =  $productcategory11_1->children()->create([
            'name'                 => 'Pressure Washers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory11_1_4 =  $productcategory11_1->children()->create([
            'name'                 => 'Pots & Accesories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory11_2 =  $productcategory11->children()->create([
            'name'                 => 'DIY',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory11_2_1 =  $productcategory11_2->children()->create([
            'name'                 => 'Power Tools',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory11_2_2 =  $productcategory11_2->children()->create([
            'name'                 => 'Hand Tools & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory11_2_3 =  $productcategory11_2->children()->create([
            'name'                 => 'Craft',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory11_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    HOME TREE
**/

        $home = category::create([
            'name'                 => 'Home',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($home->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    HOME TREE BRANCH 1 
**/

        $productcategory12 = $home->children()->create([
            'name'                 => 'Kitchen & Dining',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory12_1 =  $productcategory12->children()->create([
            'name'                 => 'Kitchen',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory12_1_1 =  $productcategory12_1->children()->create([
            'name'                 => 'Cookware Sets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory12_1_2 =  $productcategory12_1->children()->create([
            'name'                 => 'Saucepans',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory12_1_3 =  $productcategory12_1->children()->create([
            'name'                 => 'Frypans',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory12_1_4 =  $productcategory12_1->children()->create([
            'name'                 => 'Woks',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory12_1_5 =  $productcategory12_1->children()->create([
            'name'                 => 'Roasters',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory12_1_6 =  $productcategory12_1->children()->create([
            'name'                 => 'Cooking Utensils',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

       $productcategory12_1_7 =  $productcategory12_1->children()->create([
            'name'                 => 'Gourmet Food Kits',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory12_2 =  $productcategory12->children()->create([
            'name'                 => 'Oven To Table',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory12_2_1 =  $productcategory12_2->children()->create([
            'name'                 => 'Bakeware',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory12_2_2 =  $productcategory12_2->children()->create([
            'name'                 => 'Serving Dishes & Platters',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory12_3 =  $productcategory12->children()->create([
            'name'                 => 'Dining',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory12_3_1 =  $productcategory12_3->children()->create([
            'name'                 => 'Dinner Sets & Tableware',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory12_3_2 =  $productcategory12_3->children()->create([
            'name'                 => 'Glassware',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory12_3_3 =  $productcategory12_3->children()->create([
            'name'                 => 'Coffee & Tea',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_3_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 


        $productcategory12_3_4 =  $productcategory12_3->children()->create([
            'name'                 => 'Cutlery & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory12_3_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    HOME TREE BRANCH 2 
**/

        $productcategory13 = $home->children()->create([
            'name'                 => 'Household Appliances',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1 =  $productcategory13->children()->create([
            'name'                 => 'Kitchen Appliances',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory13_1_1 =  $productcategory13_1->children()->create([
            'name'                 => 'Toasters & Kettles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_2 =  $productcategory13_1->children()->create([
            'name'                 => 'Bench Mixers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_3 =  $productcategory13_1->children()->create([
            'name'                 => 'Coffee Machines',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_4 =  $productcategory13_1->children()->create([
            'name'                 => 'Blenders',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_5 =  $productcategory13_1->children()->create([
            'name'                 => 'Food Processors',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_6 =  $productcategory13_1->children()->create([
            'name'                 => 'Microwaves',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_7 =  $productcategory13_1->children()->create([
            'name'                 => 'Fridges & Freezers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_7new =  $productcategory13_1->children()->create([
            'name'                 => 'Wine Fridges',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_7new->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_8 =  $productcategory13_1->children()->create([
            'name'                 => 'Grills',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_9 =  $productcategory13_1->children()->create([
            'name'                 => 'Snack Makers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_9->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_10 =  $productcategory13_1->children()->create([
            'name'                 => 'Rice Cookers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_10->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_11 =  $productcategory13_1->children()->create([
            'name'                 => 'Slow Cookers & Multi Cookers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_11->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_12 =  $productcategory13_1->children()->create([
            'name'                 => 'Fryers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_12->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_13 =  $productcategory13_1->children()->create([
            'name'                 => 'Juicers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_13->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    HOME TREE BRANCH 3 
**/

        $productcategory14_1 =  $productcategory13->children()->create([
            'name'                 => 'Laundry & Cleaning',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory14_1_1 =  $productcategory14_1->children()->create([
            'name'                 => 'Washing Machines',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_1_2 =  $productcategory14_1->children()->create([
            'name'                 => 'Dryers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_1_3 =  $productcategory14_1->children()->create([
            'name'                 => 'Irons & Garment Steamers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_1_4 =  $productcategory14_1->children()->create([
            'name'                 => 'Vacuums & Cleaning',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_2 =  $productcategory13->children()->create([
            'name'                 => 'Heating & Cooling',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory14_2_1 =  $productcategory14_2->children()->create([
            'name'                 => 'Heaters',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_2_2 =  $productcategory14_2->children()->create([
            'name'                 => 'Fans',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_2_3 =  $productcategory14_2->children()->create([
            'name'                 => 'Air Conditioners',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_2_4 =  $productcategory14_2->children()->create([
            'name'                 => 'Dehumidifiers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_2_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory14_3 =  $productcategory13->children()->create([
            'name'                 => 'Craft',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory14_3_1 =  $productcategory14_3->children()->create([
            'name'                 => 'Sewing Machines',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory14_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    HOME TREE BRANCH 4 
**/

        $productcategory15 = $home->children()->create([
            'name'                 => 'Bed, Bath & Decor',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1 =  $productcategory15->children()->create([
            'name'                 => 'Bedroom',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory15_1_1 =  $productcategory15_1->children()->create([
            'name'                 => 'Quilt Cover Sets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_2 =  $productcategory15_1->children()->create([
            'name'                 => 'Blankets & Throws',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_3 =  $productcategory15_1->children()->create([
            'name'                 => 'Cushions & Cushion Covers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_4 =  $productcategory15_1->children()->create([
            'name'                 => 'Kids Room',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_2 =  $productcategory15->children()->create([
            'name'                 => 'Bathroom',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory15_2_2 =  $productcategory15_2->children()->create([
            'name'                 => 'Bath Towels',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_2_3 =  $productcategory15_2->children()->create([
            'name'                 => 'Beach Towels',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_2_1 =  $productcategory15_2->children()->create([
            'name'                 => 'Bath Mats & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_2_4 =  $productcategory15_2->children()->create([
            'name'                 => 'Candles & Diffusers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_2_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_3 =  $productcategory15->children()->create([
            'name'                 => 'Decor',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory15_3_1 =  $productcategory15_3->children()->create([
            'name'                 => 'Vases & Bowls',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_3_2 =  $productcategory15_3->children()->create([
            'name'                 => 'Candles & Candlesticks',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_3_3 =  $productcategory15_3->children()->create([
            'name'                 => 'Clocks',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory15_3_4 =  $productcategory15_3->children()->create([
            'name'                 => 'Frames',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory15_3_5 =  $productcategory15_3->children()->create([
            'name'                 => 'Ornaments',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

/** 
    HOME TREE BRANCH 5 
**/

        $productcategory16 = $home->children()->create([
            'name'                 => 'Beauty & Personal Care',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory16_1 =  $productcategory16->children()->create([
            'name'                 => 'Female Hair Removal',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory16_1_1 =  $productcategory16_1->children()->create([
            'name'                 => 'Epilators',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_1_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_1_2 =  $productcategory16_1->children()->create([
            'name'                 => 'Shavers & Trimmers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_1_2->id); 
        $permission->programs()->attach(1);

        $productcategory16_n1 =  $productcategory16->children()->create([
            'name'                 => 'Men\'s Grooming',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);    

        $productcategory16_n1_1 =  $productcategory16_n1->children()->create([
            'name'                 => 'Razors',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_n1_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_n1_2 =  $productcategory16_n1->children()->create([
            'name'                 => 'Shavers & Trimmers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_n1_2->id); 
        $permission->programs()->attach(1);


        $productcategory16_n1_3 =  $productcategory16_n1->children()->create([
            'name'                 => 'Shaving Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_n1_3->id); 
        $permission->programs()->attach(1);


        $productcategory16_2 =  $productcategory16->children()->create([
            'name'                 => 'Hair Care',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory16_2_1 =  $productcategory16_2->children()->create([
            'name'                 => 'Hairdryers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_2_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_2_2 =  $productcategory16_2->children()->create([
            'name'                 => 'Hair Clippers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_2_2->id); 
        $permission->programs()->attach(1);

        $productcategory16_2_3 =  $productcategory16_2->children()->create([
            'name'                 => 'Hair Styling',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_2_3->id); 
        $permission->programs()->attach(1);

        $productcategory16_2_4 =  $productcategory16_2->children()->create([
            'name'                 => 'Pet Grooming',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_2_4->id); 
        $permission->programs()->attach(1);

        $productcategory16_3 =  $productcategory16->children()->create([
            'name'                 => 'Dental Care',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory16_3_1 =  $productcategory16_3->children()->create([
            'name'                 => 'Electric Flossers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_3_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_3_2 =  $productcategory16_3->children()->create([
            'name'                 => 'Electric Toothbrushes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_3_2->id); 
        $permission->programs()->attach(1);

        $productcategory16_4 =  $productcategory16->children()->create([
            'name'                 => 'Nails',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory16_4_1 =  $productcategory16_4->children()->create([
            'name'                 => 'Manicure Sets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_4_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_4_2 =  $productcategory16_4->children()->create([
            'name'                 => 'Pedicure Sets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_4_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_5 =  $productcategory16->children()->create([
            'name'                 => 'Spa & Massage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory16_5_1 =  $productcategory16_5->children()->create([
            'name'                 => 'Massagers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_5_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_5_2 =  $productcategory16_5->children()->create([
            'name'                 => 'Foot Spas',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_5_2->id); 
        $permission->programs()->attach(1);


        $productcategory16_6 =  $productcategory16->children()->create([
            'name'                 => 'Beauty',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory16_6_1 =  $productcategory16_6->children()->create([
            'name'                 => 'Skincare',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_6_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_6_2 =  $productcategory16_6->children()->create([
            'name'                 => 'Bath & Shower',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_6_2->id); 
        $permission->programs()->attach(1);

        $productcategory16_6_3 =  $productcategory16_6->children()->create([
            'name'                 => 'Toiletry Bags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_6_3->id); 
        $permission->programs()->attach(1);

       $productcategory16_7 =  $productcategory16->children()->create([
            'name'                 => 'Fragrance',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory16_7_1 =  $productcategory16_7->children()->create([
            'name'                 => 'Men\'s Fragrance',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_7_1->id); 
        $permission->programs()->attach(1);

        $productcategory16_7_2 =  $productcategory16_7->children()->create([
            'name'                 => 'Women\'s Fragrance',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_7_2->id); 
        $permission->programs()->attach(1);

        $productcategory16_7_3 =  $productcategory16_7->children()->create([
            'name'                 => 'Home Fragrance & Candles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_7_3->id); 
        $permission->programs()->attach(1);

/** 
    HOME TREE BRANCH 6 
**/

        $productcategory17 = $home->children()->create([
            'name'                 => 'Books & Stationery',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory17_1 =  $productcategory17->children()->create([
            'name'                 => 'Books',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory17_1_1 =  $productcategory17_1->children()->create([
            'name'                 => 'Adult Fiction',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_1_1->id); 
        $permission->programs()->attach(1);

        $productcategory17_1_2 =  $productcategory17_1->children()->create([
            'name'                 => 'Biography',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_1_2->id); 
        $permission->programs()->attach(1);

        $productcategory17_1_3 =  $productcategory17_1->children()->create([
            'name'                 => 'History',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_1_3->id); 
        $permission->programs()->attach(1);

        $productcategory17_1_4 =  $productcategory17_1->children()->create([
            'name'                 => 'Travel & Lifestyle',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_1_4->id); 
        $permission->programs()->attach(1);

        $productcategory17_1_5 =  $productcategory17_1->children()->create([
            'name'                 => 'Cooking',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_1_5->id); 
        $permission->programs()->attach(1);

        $productcategory17_1_6 =  $productcategory17_1->children()->create([
            'name'                 => 'Children\'s Books',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_1_6->id); 
        $permission->programs()->attach(1);

        $productcategory17_2 =  $productcategory17->children()->create([
            'name'                 => 'Stationery',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory17_2_1 =  $productcategory17_2->children()->create([
            'name'                 => 'Notebooks & Journals',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_2_1->id); 
        $permission->programs()->attach(1);

        $productcategory17_2_2 =  $productcategory17_2->children()->create([
            'name'                 => 'Pens & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_2_2->id); 
        $permission->programs()->attach(1);

        $productcategory17_3 =  $productcategory17->children()->create([
            'name'                 => 'Magazine Subscriptions',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);         

        $productcategory17_3_1 =  $productcategory17_3->children()->create([
            'name'                 => 'Fashion',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_1->id); 
        $permission->programs()->attach(1);

        $productcategory17_3_2 =  $productcategory17_3->children()->create([
            'name'                 => 'Food & Drink',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_2->id); 
        $permission->programs()->attach(1);

        $productcategory17_3_3 =  $productcategory17_3->children()->create([
            'name'                 => 'Health & Fitness',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_3->id); 
        $permission->programs()->attach(1);

        $productcategory17_3_4 =  $productcategory17_3->children()->create([
            'name'                 => 'Home & Garden',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_4->id); 
        $permission->programs()->attach(1);

        $productcategory17_3_5 =  $productcategory17_3->children()->create([
            'name'                 => 'Boating & Fishing',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_5->id); 
        $permission->programs()->attach(1);

        $productcategory17_3_6 =  $productcategory17_3->children()->create([
            'name'                 => 'News & Current Affairs',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_6->id); 
        $permission->programs()->attach(1);

        $productcategory17_3_7 =  $productcategory17_3->children()->create([
            'name'                 => 'Art & Design',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_7->id); 
        $permission->programs()->attach(1);

        $productcategory17_3_8 =  $productcategory17_3->children()->create([
            'name'                 => 'Entertainment',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_8->id); 
        $permission->programs()->attach(1);

       $productcategory17_3_9 =  $productcategory17_3->children()->create([
            'name'                 => 'Kids & Teens',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_9->id); 
        $permission->programs()->attach(1);

       $productcategory17_3_10 =  $productcategory17_3->children()->create([
            'name'                 => 'Motoring',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_10->id); 
        $permission->programs()->attach(1);

       $productcategory17_3_11 =  $productcategory17_3->children()->create([
            'name'                 => 'Sports & Outdoors',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_11->id); 
        $permission->programs()->attach(1);

       $productcategory17_3_12 =  $productcategory17_3->children()->create([
            'name'                 => 'Hobby & Craft',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory17_3_12->id); 
        $permission->programs()->attach(1);

/** 
    FASHION TREE
**/

        $fashion = category::create([
            'name'                 => 'Fashion & Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($fashion->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    FASHION TREE BRANCH 1 
**/

        $productcategory18 = $fashion->children()->create([
            'name'                 => 'Women\'s Fashion',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory18->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory18_1 =  $productcategory18->children()->create([
            'name'                 => 'Women\'s Clothing',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory18_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);   

        $productcategory18_2 =  $productcategory18->children()->create([
            'name'                 => 'Women\'s Shoes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory18_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);
 
       $productcategory18_3 =  $productcategory18->children()->create([
            'name'                 => 'Women\'s Fashion Accesories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory18_3->id); 
        $permission->programs()->attach(1);
        $permission->programs()->attach(2);


/** 
    FASHION TREE BRANCH 2 
**/

        $productcategory19 = $fashion->children()->create([
            'name'                 => 'Men\'s Fashion',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory19->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory19_1 =  $productcategory19->children()->create([
            'name'                 => 'Men\'s Clothing',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory19_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory19_2 =  $productcategory19->children()->create([
            'name'                 => 'Men\'s Shoes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory19_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);
 
        $productcategory19_3 =  $productcategory19->children()->create([
            'name'                 => 'Men\'s Fashion Accesories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory19_3->id); 
        $permission->programs()->attach(1);
        $permission->programs()->attach(2);

   
       $productcategoryfn1 = $fashion->children()->create([
            'name'                 => 'Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryfn1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategoryfn1_1 =  $productcategoryfn1->children()->create([
            'name'                 => 'Sunglasses',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryfn1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1fn1_1_1 =  $productcategoryfn1_1->children()->create([
            'name'                 => 'Women\'s Sunglasses',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_1_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);
 
         $productcategory1fn1_1_2 =  $productcategoryfn1_1->children()->create([
            'name'                 => 'Men\'s & Unisex Sunglasses',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

         $productcategory1fn1_1_3 =  $productcategoryfn1_1->children()->create([
            'name'                 => 'Sporting Sunglasses',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategoryfn1_2 =  $productcategoryfn1->children()->create([
            'name'                 => 'Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryfn1_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1fn1_2_1 =  $productcategoryfn1_2->children()->create([
            'name'                 => 'Women\'s Fashion & Dress Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_2_2 =  $productcategoryfn1_2->children()->create([
            'name'                 => 'Women\'s Sport Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_2_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_2_3 =  $productcategoryfn1_2->children()->create([
            'name'                 => 'Women\'s Smart Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_2_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_2_4 =  $productcategoryfn1_2->children()->create([
            'name'                 => 'Men\'s Fashion & Dress Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_2_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_2_5 =  $productcategoryfn1_2->children()->create([
            'name'                 => 'Men\'s Sport Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_2_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_2_6 =  $productcategoryfn1_2->children()->create([
            'name'                 => 'Men\'s Smart Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_2_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategoryfn1_4 =  $productcategoryfn1->children()->create([
            'name'                 => 'Jewellery',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryfn1_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1fn1_4_1 =  $productcategoryfn1_4->children()->create([
            'name'                 => 'Women\'s Jewllery',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_4_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_4_2 =  $productcategoryfn1_4->children()->create([
            'name'                 => 'Men\'s Jewllery',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_4_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategoryfn1_3 =  $productcategoryfn1->children()->create([
            'name'                 => 'Luggage & Bags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategoryfn1_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1fn1_3_1 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Handbags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_2 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Wallets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_3 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Travel Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_4 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Hardside Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);        

        $productcategory1fn1_3_5 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Softside Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_6 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Carry-On Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_7 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Medium Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_8 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Large Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_9 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'X-Large Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_9->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory1fn1_3_10 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Luggage Sets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_10->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);    

        $productcategory1fn1_3_11 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Backpacks & Casual Bags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_11->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1fn1_3_12 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Business Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_12->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);   

        $productcategory1fn1_3_14 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Women\'s Business Luggage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_14->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1fn1_3_13 =  $productcategoryfn1_3->children()->create([
            'name'                 => 'Laptop Bags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1fn1_3_13->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);   


/** 
    EXPERIENCES TREE
**/

        $experiences = category::create([
            'name'                 => 'Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($experiences->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    EXPERIENCES TREE BRANCH 1 
**/


        $productcategory20 = $experiences->children()->create([
            'name'                 => 'Water Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory20_1 =  $productcategory20->children()->create([
            'name'                 => 'Jet Boat',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory20_2 =  $productcategory20->children()->create([
            'name'                 => 'Surf School',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory20_3 =  $productcategory20->children()->create([
            'name'                 => 'Kayak',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory20_4 =  $productcategory20->children()->create([
            'name'                 => 'Scuba',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory20_5 =  $productcategory20->children()->create([
            'name'                 => 'Shark Dive',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory20_6 =  $productcategory20->children()->create([
            'name'                 => 'Fishing',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory20_7 =  $productcategory20->children()->create([
            'name'                 => 'Sailing',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);


        $productcategory20_8 =  $productcategory20->children()->create([
            'name'                 => 'Other Water Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory20_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

       $productcategory21 = $experiences->children()->create([
            'name'                 => 'Flying Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory21_1 =  $productcategory21->children()->create([
            'name'                 => 'Hot Air Balloons',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory21_2 =  $productcategory21->children()->create([
            'name'                 => 'Helicopter',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory21_3 =  $productcategory21->children()->create([
            'name'                 => 'Plane',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory21_4 =  $productcategory21->children()->create([
            'name'                 => 'Indoor Sky Dive',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory21_5 =  $productcategory21->children()->create([
            'name'                 => 'Jet Pack',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory21_6 =  $productcategory21->children()->create([
            'name'                 => 'Sky Dive',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory21_7 =  $productcategory21->children()->create([
            'name'                 => 'Other Flying Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory21_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22 = $experiences->children()->create([
            'name'                 => 'Land Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory22_1 =  $productcategory22->children()->create([
            'name'                 => 'Horse Riding',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_2 =  $productcategory22->children()->create([
            'name'                 => 'Abseil',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_3 =  $productcategory22->children()->create([
            'name'                 => 'Bridge Climb',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_4 =  $productcategory22->children()->create([
            'name'                 => 'Paintball',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_5 =  $productcategory22->children()->create([
            'name'                 => 'Bungy',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_6 =  $productcategory22->children()->create([
            'name'                 => 'Aquarium & Zoo',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_8 =  $productcategory22->children()->create([
            'name'                 => 'Theme Parks',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_9 =  $productcategory22->children()->create([
            'name'                 => 'Walking Tours',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_9->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory22_10 =  $productcategory22->children()->create([
            'name'                 => 'Other Land Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory22_10->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

       $productcategory23 = $experiences->children()->create([
            'name'                 => 'Driving Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory23->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory23_1 =  $productcategory23->children()->create([
            'name'                 => 'Hot Laps',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory23_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory23_2 =  $productcategory23->children()->create([
            'name'                 => 'Quad Bikes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory23_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory23_3 =  $productcategory23->children()->create([
            'name'                 => 'Luxury Cars',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory23_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory23_4 =  $productcategory23->children()->create([
            'name'                 => 'Segways',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory23_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory23_5 =  $productcategory23->children()->create([
            'name'                 => 'Rally Driving',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory23_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory23_6 =  $productcategory23->children()->create([
            'name'                 => 'Other Driving Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory23_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory24 = $experiences->children()->create([
            'name'                 => 'Gourmet Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory24->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory24_1 =  $productcategory24->children()->create([
            'name'                 => 'Cooking Classes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory24_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory24_2 =  $productcategory24->children()->create([
            'name'                 => 'Beer Tours',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory24_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory24_3 =  $productcategory24->children()->create([
            'name'                 => 'Dining Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory24_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory24_4 =  $productcategory24->children()->create([
            'name'                 => 'High Tea',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory24_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory24_5 =  $productcategory24->children()->create([
            'name'                 => 'Other Gourmet Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory24_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory25 = $experiences->children()->create([
            'name'                 => 'Wellbeing Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory25->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory25_1 =  $productcategory25->children()->create([
            'name'                 => 'Massage',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory25_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory25_2 =  $productcategory25->children()->create([
            'name'                 => 'Spa Treatments',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory25_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory26 = $experiences->children()->create([
            'name'                 => 'Hotel Experiences',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory26->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory26_1 =  $productcategory26->children()->create([
            'name'                 => 'Romance & Seduction',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory26_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory26_2 =  $productcategory26->children()->create([
            'name'                 => 'Lazy Days',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory26_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategory26_3 =  $productcategory26->children()->create([
            'name'                 => 'Hotel Stays',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#efae09'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory26_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);


/** 
    VOUCHERS TREE
**/

        $vouchers = category::create([
            'name'                 => 'Gift Cards & e-Vouchers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#000763'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($vouchers->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

/** 
    VOUCHERS TREE BRANCH 1 
**/

        $productcategory27 = $vouchers->children()->create([
            'name'                 => 'Gift Cards',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#000763'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory27->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory28 = $vouchers->children()->create([
            'name'                 => 'e-Vouchers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#000763'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory28->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory29 = $vouchers->children()->create([
            'name'                 => 'Cinema Tickets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#000763'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory29->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n1 =  $productcategory13_1->children()->create([
            'id' => 411,
            'name'                 => 'Bread Makers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n2 =  $productcategory13_1->children()->create([
            'id' => 412,
            'name'                 => 'Ovens & Cooktops',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n3 =  $productcategory13_1->children()->create([
            'id' => 413,
            'name'                 => 'Hand Mixers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n4 =  $productcategory13_1->children()->create([
            'id' => 414,
            'name'                 => 'Scales',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n5 =  $productcategory13_1->children()->create([
            'id' => 415,
            'name'                 => 'Steamers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n6 =  $productcategory13_1->children()->create([
            'id' => 416,
            'name'                 => 'Electric Frypans & Woks',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n7 =  $productcategory13_1->children()->create([
            'id' => 417,
            'name'                 => 'Other Kitchen Appliances',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory13_1_n8 =  $productcategory13_1->children()->create([
            'id' => 437,
            'name'                 => 'Dishwashers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory13_1_n8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n1 =  $productcategory15_1->children()->create([
            'id' => 418,
            'name'                 => 'Sheets',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n2 =  $productcategory15_1->children()->create([
            'id' => 419,
            'name'                 => 'Bed Toppers & Underlays',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n3 =  $productcategory15_1->children()->create([
            'id' => 420,
            'name'                 => 'Mattress & Pillow Protectors',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n4 =  $productcategory15_1->children()->create([
            'id' => 421,
            'name'                 => 'Pillows',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n5 =  $productcategory15_1->children()->create([
            'id' => 422,
            'name'                 => 'Pillow Cases',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n6 =  $productcategory15_1->children()->create([
            'id'   => 423,
            'name'                 => 'Quilts',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n7 =  $productcategory15_1->children()->create([
            'id'   => 424,
            'name'                 => 'Single',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n8 =  $productcategory15_1->children()->create([
            'id'   => 425,
            'name'                 => 'Double',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n9 =  $productcategory15_1->children()->create([
            'id'   => 426,
            'name'                 => 'Queen',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n9->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n10 =  $productcategory15_1->children()->create([
            'id'   => 427,
            'name'                 => 'King',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n10->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n11 =  $productcategory15_1->children()->create([
            'id'   => 428,
            'name'                 => 'Super King',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n11->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_1_n12 =  $productcategory15_1->children()->create([
            'id'   => 432,
            'name'                 => 'King Single',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_1_n12->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_2_n1 =  $productcategory15_2->children()->create([
            'id' => 429,
            'name'                 => 'Bath Robes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_2_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory16_5_n1 =  $productcategory16_5->children()->create([
            'id' => 430,
            'name'                 => 'Bath Robes',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_5_n1->id); 
        $permission->programs()->attach(1);

        $productcategory16_5_n2 =  $productcategory16_5->children()->create([
            'id' => 431,
            'name'                 => 'Hot Water Bottles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory16_5_n2->id); 
        $permission->programs()->attach(1);

        $productcategory15_3_n1 =  $productcategory15_3->children()->create([
            'id' => 433,
            'name'                 => 'Lighting',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_3_n2 =  $productcategory15_3->children()->create([
            'id' => 434,
            'name'                 => 'Door Stops',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_n2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_3_n3 =  $productcategory15_3->children()->create([
            'id' => 435,
            'name'                 => 'Cushions & Cushion Covers',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_n3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory15_3_n4 =  $productcategory15_3->children()->create([
            'id' => 436,
            'name'                 => 'Throws',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory15_3_n4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_4_1 =  $productcategory1_4->children()->create([
            'id' => 438,
            'name'                 => 'Wi-Fi',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_4_1->id); 
        $permission->programs()->attach(1);         
     
        $productcategory1_4_2 =  $productcategory1_4->children()->create([
            'id' => 440,
            'name'                 => 'Home Assistant',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_4_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_4_3 =  $productcategory1_4->children()->create([
            'id' => 441,
            'name'                 => 'Wireless Audio',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_4_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 


        $productcategory2_2_n1 =  $productcategory2_2->children()->create([
            'id' => 439,
            'name'                 => 'Wireless Audio',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory2_2_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1_1_n1 =  $productcategory1_1->children()->create([
            'id' => 442,
            'name'                 => 'HD TV',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        $productcategory1_1_n2 =  $productcategory1_1->children()->create([
            'id' => 443,
            'name'                 => '58"',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#1b8fe8'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1_1_n2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategory1xfn1_2_1 =  $productcategoryfn1_2->children()->create([
            'id' => 444,
            'name'                 => 'Kids Watches',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#cf0bdd'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory1xfn1_2_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);

        $productcategorybaby = $home->children()->create([
            'id' => 446,
            'name'                 => 'Baby',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorybaby->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorybaby_1 = $productcategorybaby->children()->create([
            'id' => 447,
            'name'                 => 'Nappy Bags',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorybaby_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorybaby_2 = $productcategorybaby->children()->create([
            'id' => 448,
            'name'                 => 'Nursery',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorybaby_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorybaby_3 = $productcategorybaby->children()->create([
            'id' => 449,
            'name'                 => 'Baby Accessories',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorybaby_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys = $home->children()->create([
            'id' => 450,
            'name'                 => 'Toys',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_1 = $productcategorytoys->children()->create([
            'id' => 451,
            'name'                 => 'Baby & Toddler Toys',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_2 = $productcategorytoys->children()->create([
            'id' => 452,
            'name'                 => 'Construction',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_2->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_3 = $productcategorytoys->children()->create([
            'id' => 453,
            'name'                 => 'Games',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_3->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_4 = $productcategorytoys->children()->create([
            'id' => 454,
            'name'                 => 'Puzzles & Crafts',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_4->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_5 = $productcategorytoys->children()->create([
            'id' => 455,
            'name'                 => 'Collectibles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_5->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_6 = $productcategorytoys->children()->create([
            'id' => 456,
            'name'                 => 'Dollss',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_6->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_7 = $productcategorytoys->children()->create([
            'id' => 457,
            'name'                 => 'RC Vehicles',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_7->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

        $productcategorytoys_8 = $productcategorytoys->children()->create([
            'id' => 458,
            'name'                 => 'Kids Toys',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#33b500'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategorytoys_8->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2); 

       $productcategory10_3_n1 =  $productcategory10_3->children()->create([
            'id' => 459,
            'name'                 => 'Trampolines',
            'description' => '',
            'type'  => 'product',
            'main_color' => '#aa2a02'
        ]);

        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($productcategory10_3_n1->id); 
        $permission->programs()->attach(1);         
        $permission->programs()->attach(2);  

        /** POST CATEGORIES! **/

        $category22 = category::create([
            'name'                 => 'Company Update',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);          
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category22->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category23 = category::create([
            'name'                 => 'Parts',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);                                                    
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category23->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category24 = category::create([
            'name'                 => 'Service',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);  
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category24->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category25 = category::create([
            'name'                 => 'Maitenance',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]); 
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category25->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category26 = category::create([
            'name'                 => 'Tips & Tricks',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);  
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category26->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category27 = category::create([
            'name'                 => 'Reminders',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);  
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category27->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category28 = category::create([
            'name'                 => 'Service Update',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);     
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category28->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category29 = category::create([
            'name'                 => 'IT Update',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);     
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category29->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category30 = category::create([
            'name'                 => 'Social Team Reminder',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#33aa60'
        ]);     
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category30->id); 
        $permission->programs()->attach(1); 
        $permission->programs()->attach(2); 

        $category31 = category::create([
            'name'                 => 'Best Works',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris. Sed interdum in elit at ornare. Fusce nec neque vitae sem mattis viverra id et sem.',
            'type'  => 'post',
            'main_color' => '#3498db'
        ]);     
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->categories()->attach($category31->id); 
        $permission->programs()->attach(2); 
        $permission->programs()->attach(1); 

    }
}