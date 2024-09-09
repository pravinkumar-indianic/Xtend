<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','full_name') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('full_name')->nullable();

	    	});
         }
	   

        if (Schema::hasColumn('users','wishlist_array') == false) {

		    Schema::table('users', function($table)
	    	{
	        	$table->text('wishlist_array')->nullable();
	    	});

	    }

        if (Schema::hasColumn('users','cart_array') == false) {

		    Schema::table('users', function($table)
	    	{

	        	$table->text('cart_array')->nullable();

        	});
	    }
	}

	public function down()
	{

        if (Schema::hasColumn('users','full_name')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('full_name');

	        });
	    }

        if (Schema::hasColumn('users','wishlist_array')) {

		    Schema::table('users', function($table)
	    	{
	        	$table->dropColumn('wishlist_array');
	    	});

	    }

        if (Schema::hasColumn('users','cart_array')) {

		    Schema::table('users', function($table)
	    	{

	        	$table->dropColumn('cart_array');

        	 });

		}
	}
}