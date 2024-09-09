<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankOrderProducts extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_order_products')){

            Schema::create('codengine_awardbank_order_products', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('order_id');
                $table->integer('product_id');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_order_products');
    }
}
