<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrizes4 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_prizes','multi_prize') == false) {

            Schema::table('codengine_awardbank_prizes', function($table)
            {
                $table->boolean('multi_prize')->default(0);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_prizes','multi_prize')) {

            Schema::table('codengine_awardbank_prizes', function($table)
            {
                $table->dropColumn('multi_prize');
            });
        }
    }
}
