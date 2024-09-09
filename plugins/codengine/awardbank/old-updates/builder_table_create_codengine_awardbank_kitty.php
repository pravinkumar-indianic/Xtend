<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankKitty extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_kitty')){

            Schema::create('codengine_awardbank_kitty', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('transaction_id');
                $table->integer('credit_id');
                $table->boolean('spent');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_kitty');
    }
}
