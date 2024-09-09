<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrderLineItem3 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_order_line_item','purchase_order_id') == false) {

            Schema::table('codengine_awardbank_order_line_item', function($table)
            {
                $table->string('purchase_order_id')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_order_line_item','purchase_order_id')) {

            Schema::table('codengine_awardbank_order_line_item', function($table)
            {
                $table->dropColumn('purchase_order_id')->nullable();
            });
        }
    }
}
