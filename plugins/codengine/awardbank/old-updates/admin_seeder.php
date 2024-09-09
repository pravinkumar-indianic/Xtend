<?php namespace Codengine\Awardbank\Updates;

use Backend\Facades\BackendAuth;
use Schema;
use Seeder;
use Db;

class AdminSeeder extends Seeder
{
    public function run()
    {

		Db::table('backend_user_roles')->insert([
			'id' => 3,
			'name' => 'Content Manager',
			'code' => 'content_manager',
			'description' => 'Content Manager',
			'is_system' => 0,
			'permissions' => '{"codengine.awardbank.content_manager":"1","backend.access_dashboard":"1","media.manage_media":"1","rainlab.users.access_settings":"1","rainlab.users.access_groups":"1","rainlab.users.access_users":"1","rainlab.users.impersonate_user":"1"}'

		]);

		Db::table('backend_user_roles')->insert([
			'id' => 4,
			'name' => 'Supplier Manager',
			'code' => 'supplier_manager',
			'description' => 'Supplier Manager',
			'is_system' => 0,
			'permissions' => '{"codengine.awardbank.supplier_manager":"1","backend.access_dashboard":"1","media.manage_media":"1","rainlab.users.access_settings":"1","rainlab.users.access_groups":"1","rainlab.users.access_users":"1","rainlab.users.impersonate_user":"1"}'

		]);

		BackendAuth::register([
			'id' => 1,
		    'first_name' => 'Mercedes',
		    'last_name' => 'EVT',
		    'login' => 'Mercedes1',
		    'email' => 'mercedes@evtmarketing.com.au',
		    'password' => 'Mercedes1!',
		    'password_confirmation' => 'Mercedes1!',
		    'is_activated' => 1,
		    'is_superuser' => 1,   
		    'role_id' => 1,
		]);

		BackendAuth::register([
			'id' => 2,
		    'first_name' => 'Anthony',
		    'last_name' => 'EVT',
		    'login' => 'Anthony1',
		    'email' => 'anthony@evtmarketing.com.au',
		    'password' => 'Anthony1!',
		    'password_confirmation' => 'Anthony1!',
		    'is_activated' => 1,  
		    'is_superuser' => 1,   
		    'role_id' => 1,		    		     
		]);		

		BackendAuth::register([
			'id' => 3,
		    'first_name' => 'Kieran',
		    'last_name' => 'EVT',
		    'login' => 'Kieran1',
		    'email' => 'kieran@evtmarketing.com.au',
		    'password' => 'Kieran1!',
		    'password_confirmation' => 'Kieran1!',
		    'is_activated' => 1,   
		    'role_id' => 3,		    
		]);	

		BackendAuth::register([
			'id' => 4,
		    'first_name' => 'Juanita',
		    'last_name' => 'EVT',
		    'login' => 'Juanita1',
		    'email' => 'juanita@evtmarketing.com.au',
		    'password' => 'Juanita1!',
		    'password_confirmation' => 'Juanita1!',
		    'is_activated' => 1, 
		    'role_id' => 4,			      
		]);	

		BackendAuth::register([
			'id' => 5,
		    'first_name' => 'Josh',
		    'last_name' => 'EVT',
		    'login' => 'Josh1',
		    'email' => 'josh@evtmarketing.com.au',
		    'password' => 'Josh1!',
		    'password_confirmation' => 'Josh1!',
		    'is_activated' => 1,  
		    'role_id' => 4,			     
		]);	

		BackendAuth::register([
			'id' => 6,
		    'first_name' => 'Laura',
		    'last_name' => 'EVT',
		    'login' => 'Laura1',
		    'email' => 'laura@evtmarketing.com.au',
		    'password' => 'Laura1!',
		    'password_confirmation' => 'Laura1!',
		    'is_activated' => 1,  
		    'role_id' => 3,			     
		]);		

		BackendAuth::register([
			'id' => 7,
		    'first_name' => 'Gina',
		    'last_name' => 'EVT',
		    'login' => 'Gina1',
		    'email' => 'gina@evtmarketing.com.au',
		    'password' => 'Gina1!',
		    'password_confirmation' => 'Gina1!',
		    'is_activated' => 1,   
		    'role_id' => 4,			    
		]);	

		Db::table('system_settings')->insert([
			'id' => 1,
			'item' => 'backend_brand_settings',
			'value' => '{"app_name":"Xtend System","app_tagline":"Incentivise","primary_color":"#836cb0","secondary_color":"#eb176f","accent_color":"#3ed0c7","menu_mode":"collapse"}'
		]);		

	}
}