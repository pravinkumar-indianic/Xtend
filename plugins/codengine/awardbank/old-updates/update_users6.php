<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers6 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','company_position') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->string('company_position')->nullable();

		    });
	    }

  
	}

	public function down()
	{
       if (Schema::hasColumn('users','company_position')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('company_position')->nullable();

		    });
	    }
	}
}