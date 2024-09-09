<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards3 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','primary_color') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->string('primary_color', 255)->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','secondary_color') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->string('secondary_color', 255)->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_awards','primary_color')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('primary_color');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','secondary_color')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('secondary_color');
            });
        }
    }
}
