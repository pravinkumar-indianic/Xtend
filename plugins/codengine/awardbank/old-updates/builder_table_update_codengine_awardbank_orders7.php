<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders7 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','order_notes') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->text('order_notes')->nullable();
            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_orders','order_notes')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_notes')->nullable();
            });
        }
 
    }
}
