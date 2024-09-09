<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankVotes2 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_votes','region_id') == false) {

            Schema::table('codengine_awardbank_votes', function($table)
            {
                $table->integer('region_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_votes','team_id') == false) {

            Schema::table('codengine_awardbank_votes', function($table)
            {
                $table->integer('team_id')->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_votes','region_id')) {

            Schema::table('codengine_awardbank_votes', function($table)
            {
                $table->dropColumn('region_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_votes','team_id')) {

            Schema::table('codengine_awardbank_votes', function($table)
            {
                $table->dropColumn('team_id')->nullable();
            });
        }
    }
}
