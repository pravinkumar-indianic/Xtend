<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms17 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','new_transfer_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_transfer_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_transfer_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_transfer_template')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','new_transfer_send')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_transfer_send');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_transfer_template')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('new_transfer_template');
            });
        }
    }
}