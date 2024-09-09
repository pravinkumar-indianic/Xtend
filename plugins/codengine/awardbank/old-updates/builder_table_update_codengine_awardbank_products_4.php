<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankProducts4 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_products','options1') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->text('options1')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','options2') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->text('options2')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','options3') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->text('options3')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_products','options1')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('options1')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','options2')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('options2')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','options3')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('options3')->nullable();
            });
        }

    }
}
