<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms11 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','program_t_and_c') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('program_t_and_c')->nullable();

            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','program_t_and_c')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('program_t_and_c');
            });
        }
    }
}