<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers5 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','owner_array') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('owner_array')->nullable();

		    });
	    }

  
	}

	public function down()
	{
       if (Schema::hasColumn('users','owner_array')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('owner_array')->nullable();

		    });
	    }
	}
}