<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders3 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','order_id') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_id', 50);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','order_id')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_id');
            });
        }
    }
}
