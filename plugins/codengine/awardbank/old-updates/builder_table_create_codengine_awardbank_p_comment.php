<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPComment extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_p_comment')){

            Schema::create('codengine_awardbank_p_comment', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('comment_id');
                $table->integer('commentable_id');
                $table->string('commentable_type');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_p_comment');
    }
}
