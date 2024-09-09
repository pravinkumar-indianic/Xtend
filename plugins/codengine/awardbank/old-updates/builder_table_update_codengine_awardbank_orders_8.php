<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders8 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','order_phone') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_phone')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','delivery_notes') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('delivery_notes')->nullable();
            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_orders','order_phone')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_phone')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','delivery_notes')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('delivery_notes')->nullable();
            });
        } 
    }
}
