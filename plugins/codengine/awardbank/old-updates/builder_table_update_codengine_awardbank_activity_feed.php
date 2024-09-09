<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankActivityFeed extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_activity_feed','type') == false) {

            Schema::table('codengine_awardbank_activity_feed', function($table)
            {
                $table->string('type')->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_activity_feed','type')) {

            Schema::table('codengine_awardbank_activity_feed', function($table)
            {
                $table->dropColumn('type');
            });
        }

    }
}
