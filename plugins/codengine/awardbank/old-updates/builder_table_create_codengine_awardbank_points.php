<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPoints extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_points')){

            Schema::create('codengine_awardbank_points', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('transaction_id')->default(0);
                $table->integer('credit_id')->default(0);;
                $table->boolean('spent')->default(0);
                $table->boolean('pending')->default(0);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_points');
    }
}
