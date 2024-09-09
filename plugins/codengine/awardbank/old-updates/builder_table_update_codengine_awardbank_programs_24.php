<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms24 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','use_business_names') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('use_business_names')->default(0);

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','use_business_names')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('use_business_names');
            });
        }
    }
}