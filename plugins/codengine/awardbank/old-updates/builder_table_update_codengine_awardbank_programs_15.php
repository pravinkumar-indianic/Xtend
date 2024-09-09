<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms15 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','new_birthday_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_birthday_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_birthday_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_birthday_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_tenure_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_tenure_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_tenure_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_tenure_template')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','new_birthday_send')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_birthday_send');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_birthday_template')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_birthday_template');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_tenure_send')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_tenure_send');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_tenure_template')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_tenure_template');
            });
        }
    }
}