<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers3 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','can_buy_points') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->boolean('can_buy_points')->default(1);

		    });
	    }

        if (Schema::hasColumn('users','points_limit') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('points_limit')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','external_reference') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->string('external_reference')->nullable();

		    });
	    }
	}

	public function down()
	{
        if (Schema::hasColumn('users','can_buy_points')) {
        	
		    Schema::table('users', function($table)
		    {
		        $table->dropColumn('can_buy_points');
		    });
	    }

        if (Schema::hasColumn('users','points_limit')) {
        	
		    Schema::table('users', function($table)
		    {
		        $table->dropColumn('points_limit');
		    });
	    }

        if (Schema::hasColumn('users','external_reference')) {
        	
		    Schema::table('users', function($table)
		    {
		        $table->dropColumn('external_reference');
		    });
	    }
	}
}