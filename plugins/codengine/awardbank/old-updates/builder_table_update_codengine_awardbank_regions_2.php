<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankRegions2 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_regions','slug') == false) {

            Schema::table('codengine_awardbank_regions', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_regions','slug')) {

            Schema::table('codengine_awardbank_regions', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
