<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers2 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','slug') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('slug')->nullable();

		    });
	    }
	}

	public function down()
	{
        if (Schema::hasColumn('users','slug')) {
        	
		    Schema::table('users', function($table)
		    {
		        $table->dropColumn('slug');
		    });
	    }
	}
}