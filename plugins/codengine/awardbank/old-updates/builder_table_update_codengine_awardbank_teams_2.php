<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankTeams2 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('codengine_awardbank_teams','can_buy_points') == false) {

		    Schema::table('codengine_awardbank_teams', function($table)
		    {
		    	
		        $table->boolean('can_buy_points')->default(1);

		    });
	    }

        if (Schema::hasColumn('codengine_awardbank_teams','points_limit') == false) {

		    Schema::table('codengine_awardbank_teams', function($table)
		    {
		    	
		        $table->boolean('points_limit')->nullable();

		    });
	    }

        if (Schema::hasColumn('codengine_awardbank_teams','external_reference') == false) {

		    Schema::table('codengine_awardbank_teams', function($table)
		    {
		    	
		        $table->string('external_reference')->nullable();

		    });
	    }
	}

	public function down()
	{
        if (Schema::hasColumn('codengine_awardbank_teams','can_buy_points')) {
        	
		    Schema::table('codengine_awardbank_teams', function($table)
		    {
		        $table->dropColumn('can_buy_points');
		    });
	    }

        if (Schema::hasColumn('codengine_awardbank_teams','points_limit')) {
        	
		    Schema::table('codengine_awardbank_teams', function($table)
		    {
		        $table->dropColumn('points_limit');
		    });
	    }

        if (Schema::hasColumn('codengine_awardbank_teams','external_reference')) {
        	
		    Schema::table('codengine_awardbank_teams', function($table)
		    {
		        $table->dropColumn('external_reference');
		    });
	    }
	}
}