<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrizes3 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_prizes','description') == false) {

            Schema::table('codengine_awardbank_prizes', function($table)
            {
                $table->text('description')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_prizes','description')) {

            Schema::table('codengine_awardbank_prizes', function($table)
            {
                $table->dropColumn('description');
            });
        }
    }
}
