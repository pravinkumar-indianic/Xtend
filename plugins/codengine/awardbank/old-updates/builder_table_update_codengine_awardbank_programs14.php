<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms14 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','celebrate_birthdays') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('celebrate_birthdays')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','birthdays_array') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('birthdays_array')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','celebrate_tenure') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('celebrate_tenure')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','tenure_array') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('tenure_array')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','celebrate_birthdays')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('celebrate_birthdays');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','birthdays_array')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('birthdays_array');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','celebrate_tenure')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('celebrate_tenure');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','tenure_array')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('tenure_array');
            });
        }
    }
}