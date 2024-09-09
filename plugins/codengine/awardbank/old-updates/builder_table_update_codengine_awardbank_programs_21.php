<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms21 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','cancellation_active') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('cancellation_active')->default(0);

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','cancellation_active')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('cancellation_active');
            });
        }
    }
}