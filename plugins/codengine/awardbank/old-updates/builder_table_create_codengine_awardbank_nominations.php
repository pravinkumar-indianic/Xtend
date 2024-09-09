<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankNominations extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_nominations')){
            
            Schema::create('codengine_awardbank_nominations', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('award_id');
                $table->integer('nominated_user_id');
                $table->boolean('is_winner')->default(false);
                $table->integer('created_user_id');
                $table->integer('approved_user_id')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->integer('form_result_id')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_nominations');
    }
}