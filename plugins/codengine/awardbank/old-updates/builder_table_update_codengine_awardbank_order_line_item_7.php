<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrderLineItem7 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->integer('product_volume')->default(1);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->dropColumn('product_volume');
        });
    }
}
