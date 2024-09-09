<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankProducts5 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_products','territory') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->string('territory')->nullable();
            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_products','territory')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('territory')->nullable();
            });
        }
    }
}
