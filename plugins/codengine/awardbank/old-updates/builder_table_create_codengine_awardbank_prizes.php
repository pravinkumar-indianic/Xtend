<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPrizes extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_prizes')){

            Schema::create('codengine_awardbank_prizes', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('award_id');
                $table->integer('winner_id')->nullable();
                $table->string('name');
                $table->integer('order');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_prizes');
    }
}