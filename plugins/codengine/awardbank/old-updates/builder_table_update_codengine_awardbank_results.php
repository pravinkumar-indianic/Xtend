<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResults extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_results','label') == false) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->string('label', 50);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_results','label')) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->dropColumn('label');
            });
        }
    }
}
