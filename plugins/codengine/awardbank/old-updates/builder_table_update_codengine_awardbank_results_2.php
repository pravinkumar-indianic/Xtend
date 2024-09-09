<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResults2 extends Migration
{
    public function up()
    {


      	if (Schema::hasColumn('codengine_awardbank_results','region_id') == false) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->integer('region_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_results','team_id') == false) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->integer('team_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_results','user_id') == false) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->integer('user_id')->nullable();
            });
        }
        if (Schema::hasColumn('codengine_awardbank_results','is_current') == false) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->boolean('is_current')->default(1);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_results','region_id')) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->dropColumn('region_id');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_results','team_id')) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->dropColumn('team_id');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_results','user_id')) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_results','is_current')) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->dropColumn('is_current');
            });
        }
    }
}







