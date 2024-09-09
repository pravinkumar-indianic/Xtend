<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPW extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_p_w')){

            Schema::create('codengine_awardbank_p_w', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('prize_id')->unsigned();
                $table->integer('user_id')->unsigned();
                //$table->primary(['prize_id', 'user_id']);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_p_w');
    }
}