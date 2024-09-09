<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms16 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','register_form_questions') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('register_form_questions')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','register_form_questions')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('register_form_questions');
            });
        }
    }
}