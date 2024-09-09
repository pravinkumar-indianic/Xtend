<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers12 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','email_random_string') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->string('email_random_string')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','email_confirmed') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->boolean('email_confirmed')->default(0);

		    });
	    }
	}

	public function down()
	{

       if (Schema::hasColumn('users','email_random_string')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('email_random_string');

		    });
	    }

       if (Schema::hasColumn('users','email_confirmed')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('email_confirmed');

		    });
	    }
	}
}