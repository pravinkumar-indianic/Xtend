<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards4 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','hide_nominations_tab') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->boolean('hide_nominations_tab', 255)->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','hide_voting_tab') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->boolean('hide_voting_tab', 255)->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','prize_display_string') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->string('prize_display_string', 255)->default('Prize');
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_awards','hide_nominations_tab')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('hide_nominations_tab');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','hide_voting_tab')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('hide_voting_tab');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','prize_display_string')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('prize_display_string');
            });
        }
    }
}
