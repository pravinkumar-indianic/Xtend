<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers8 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','work_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('work_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','home_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('home_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','shipping_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('shipping_id')->nullable();

		    });
	    }

  
	}

	public function down()
	{

       if (Schema::hasColumn('users','work_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('work_id')->nullable();

		    });
	    }

       if (Schema::hasColumn('users','home_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('home_id')->nullable();

		    });
	    }

       if (Schema::hasColumn('users','shipping_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('shipping_id')->nullable();

		    });
	    }
	}
}