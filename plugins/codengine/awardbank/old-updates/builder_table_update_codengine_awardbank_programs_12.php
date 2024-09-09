<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms12 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','registration_open') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('registration_open')->default(0);

            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','registration_open')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('registration_open');
            });
        }
    }
}