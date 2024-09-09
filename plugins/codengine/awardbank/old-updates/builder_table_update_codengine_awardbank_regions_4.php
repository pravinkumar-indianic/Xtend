<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankRegions4 extends Migration
{

	public function up()
	{
        if (Schema::hasColumn('codengine_awardbank_regions','program_id')) {

		    Schema::table('codengine_awardbank_regions', function($table)
		    {
		        $table->integer('program_id')->nullable()->change();
		    });

	    }
	}

	public function down()
	{

        if (Schema::hasColumn('codengine_awardbank_regions','program_id')) {

		    Schema::table('codengine_awardbank_regions', function($table)
		    {
		        $table->dropColumn('program_id');
		    });

	    }
	}

}

