<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers7 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','current_territory') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->string('current_territory')->nullable();

		    });
	    }

  
	}

	public function down()
	{
       if (Schema::hasColumn('users','current_territory')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_territory')->nullable();

		    });
	    }
	}
}