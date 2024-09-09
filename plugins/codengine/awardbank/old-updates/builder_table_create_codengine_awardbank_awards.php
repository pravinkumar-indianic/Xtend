<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankAwards extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_awards')){

            Schema::create('codengine_awardbank_awards', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->boolean('nominations_approval_required');
                $table->boolean('nominations_public');            
                $table->text('nomination_questions_json')->nullable();
                $table->dateTime('nominations_open_at')->nullable();
                $table->dateTime('nominations_closed_at')->nullable();
                $table->boolean('votes_approval_required')->nullable();
                $table->boolean('votes_public');              
                $table->text('votes_question_json')->nullable();
                $table->dateTime('votes_open_at')->nullable();
                $table->dateTime('votes_close_at')->nullable();
                $table->dateTime('award_open_at');
                $table->dateTime('award_close_at');
                $table->string('outcome_is')->nullable();
                $table->string('name');
                $table->text('description');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_awards');
    }
}