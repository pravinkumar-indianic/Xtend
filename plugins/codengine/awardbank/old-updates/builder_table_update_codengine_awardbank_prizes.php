<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrizes extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_prizes','prize_value') == false) {

            Schema::table('codengine_awardbank_prizes', function($table)
            {
                $table->integer('prize_value')->default(0);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_prizes','prize_value')) {

            Schema::table('codengine_awardbank_prizes', function($table)
            {
                $table->dropColumn('prize_value');
            });
        }
    }
}
