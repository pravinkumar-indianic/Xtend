<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankDelivery extends Migration
{
    public function up()
    {
        
        if(!Schema::hasTable('codengine_awardbank_delivery')){

            Schema::create('codengine_awardbank_delivery', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('detail', 255)->nullable();
                $table->string('shipping_base', 50);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_delivery');
    }
}
