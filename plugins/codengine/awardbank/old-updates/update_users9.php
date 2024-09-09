<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers9 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','current_points') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_points')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','pending_points') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('pending_points')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','spent_points') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('spent_points')->nullable();

		    });
	    }

 
     	if (Schema::hasColumn('users','lifetime_points') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('lifetime_points')->nullable();

		    });
	    } 
	}

	public function down()
	{

       if (Schema::hasColumn('users','current_points')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_points')->nullable();

		    });
	    }

       if (Schema::hasColumn('users','pending_points')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('pending_points')->nullable();

		    });
	    }

       	if (Schema::hasColumn('users','spent_points')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('spent_points')->nullable();

		    });
	    }

       	if (Schema::hasColumn('users','lifetime_points')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('lifetime_points')->nullable();

		    });
	    }
	}
}