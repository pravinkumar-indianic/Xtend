<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankResultType extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_result_type')){

            Schema::create('codengine_awardbank_result_type', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('type');
                $table->integer('benchmark');
                $table->integer('allocated_points_value');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_result_type');
    }
}
