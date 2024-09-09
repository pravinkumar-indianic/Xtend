<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms18 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','is_activated') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('is_activated')->default(0);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_created_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_created_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_created_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('new_created_template')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','is_activated')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('is_activated');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_created_send')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_created_send');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_created_template')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_created_template');
            });
        }
    }
}