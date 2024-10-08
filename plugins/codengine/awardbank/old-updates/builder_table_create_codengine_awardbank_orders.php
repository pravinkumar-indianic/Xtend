<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankOrders extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_orders')){

            Schema::create('codengine_awardbank_orders', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('points_value')->nullable(); 
                $table->integer('points_value_delivered')->nullable(); 
                $table->integer('user_id')->nullable(); 
                $table->integer('shipping_address_id')->nullable(); 
                $table->boolean('full_processed')->default(0);

            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_orders');
    }
}
