<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankTargetingTags extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_targeting_tags')){

            Schema::create('codengine_awardbank_targeting_tags', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('name');
            });
        }

        if(!Schema::hasTable('codengine_awardbank_tt_user')){

            Schema::create('codengine_awardbank_tt_user', function($table)
            {
                $table->integer('targeting_tag_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->primary(['targeting_tag_id', 'user_id']);
            });
        }

        /**
        if(!Schema::hasTable('codengine_awardbank_tt_program')){

            Schema::create('codengine_awardbank_tt_program', function($table)
            {
                $table->integer('targeting_tag_id')->unsigned();
                $table->integer('program_id')->unsigned();
                $table->primary(['targeting_tag_id', 'program_id']);
            });
        }
        **/
        
        if(!Schema::hasTable('codengine_awardbank_tt_post')){

            Schema::create('codengine_awardbank_tt_post', function($table)
            {
                $table->integer('targeting_tag_id')->unsigned();
                $table->integer('post_id')->unsigned();
                $table->primary(['targeting_tag_id', 'post_id']);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_targeting_tags');
        Schema::dropIfExists('codengine_awardbank_tt_user');
        Schema::dropIfExists('codengine_awardbank_tt_program');
        Schema::dropIfExists('codengine_awardbank_tt_post');
    }
}
