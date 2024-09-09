<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders_6 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_orders', function($table)
        {
            $table->string('order_id', 50)->nullable()->change();
            $table->dropColumn('shipping_point');
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_orders', function($table)
        {
            $table->string('order_id', 50)->nullable(false)->change();
            $table->integer('shipping_point');
        });
    }
}