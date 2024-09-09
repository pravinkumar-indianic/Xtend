<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms22 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','new_spend_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_spend_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_spend_reminder') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_spend_reminder')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','new_spend_send')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_spend_send');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_spend_reminder')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_spend_reminder');
            });
        }
    }
}