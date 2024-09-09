<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankVotes extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_votes')){

            Schema::create('codengine_awardbank_votes', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('nomination_id');
                $table->integer('voter_id');
                $table->integer('approver_user_id')->nullable();
                $table->integer('form_result_id')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_votes');
    }
}