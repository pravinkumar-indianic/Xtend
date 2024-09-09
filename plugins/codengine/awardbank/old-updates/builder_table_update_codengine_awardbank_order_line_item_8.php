<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrderLineItem8 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->integer('order_placer_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->dropColumn('order_placer_id');
        });
    }
}
