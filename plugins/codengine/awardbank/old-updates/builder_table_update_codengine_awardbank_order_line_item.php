<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrderLineItem extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->text('order_customer_address')->nullable();
            $table->string('order_customer_email')->nullable();
            $table->string('order_customer_phone_number')->nullable();
            $table->string('order_supplier_name')->nullable();
            $table->string('order_program_id')->nullable();
            $table->string('order_program_name')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->dropColumn('order_customer_address');
            $table->dropColumn('order_customer_email');
            $table->dropColumn('order_customer_phone_number');
            $table->dropColumn('order_supplier_name');
            $table->dropColumn('order_program_id');
            $table->dropColumn('order_program_name');
        });
    }
}
