<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankOrderLineItem extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_order_line_item', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('product_dollar_value')->nullable();
            $table->string('product_slug')->nullable();
            $table->boolean('product_deliver_base')->nullable();
            $table->text('product_category_array')->nullable();
            $table->integer('product_supplier_id')->nullable();
            $table->string('product_supplier_name')->nullable();
            $table->integer('product_status')->nullable();
            $table->boolean('product_voucher')->nullable();
            $table->string('product_voucher_code')->nullable();
            $table->dateTime('shipped_date')->nullable();
            $table->dateTime('arrived_date')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('connote_id')->nullable();
            $table->string('shipping_id')->nullable(); 
            $table->string('shipping_name')->nullable();            
            $table->text('option1_selection')->nullable();
            $table->text('option2_selection')->nullable();
            $table->text('option3_selection')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_order_line_item');
    }
}