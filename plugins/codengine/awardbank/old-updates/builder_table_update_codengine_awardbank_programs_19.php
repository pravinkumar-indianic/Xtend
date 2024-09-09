<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms19 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','manual_pricing') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('manual_pricing')->default(0);

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','manual_pricing')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('manual_pricing');
            });
        }
    }
}