<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers10 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','t_and_c_accept') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->boolean('t_and_c_accept')->default(0);

		    });
	    }
	}

	public function down()
	{

       if (Schema::hasColumn('users','t_and_c_accept')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('t_and_c_accept');

		    });
	    }
	}
}