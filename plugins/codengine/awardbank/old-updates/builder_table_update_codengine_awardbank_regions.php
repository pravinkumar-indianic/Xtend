<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankRegions extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_regions','program_id') == false) {

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
                $table->integer('program_id')->nullable(false)->change();
            });
        }
    }
}
