<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders2 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_orders','shipping_point') == false) {
            
            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->integer('shipping_point');
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','shipping_point')) { 

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('shipping_point');
            });

        }
    }
}
