<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms13 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','region_label') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('region_label')->default('Region');

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','team_label') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('team_label')->default('Team');

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','category_exclusion_array') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('category_exclusion_array')->nullable();

            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','region_label')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('region_label');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','team_label')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('team_label');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','category_exclusion_array')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('category_exclusion_array');
            });
        }
    }
}