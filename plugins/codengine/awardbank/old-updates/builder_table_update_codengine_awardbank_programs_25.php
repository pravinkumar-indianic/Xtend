<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms25 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','use_targeting_tags') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('use_targeting_tags')->default(0);

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','use_targeting_tags')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('use_targeting_tags');
            });
        }
    }
}