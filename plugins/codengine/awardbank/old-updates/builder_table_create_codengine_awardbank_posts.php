<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPosts extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_posts')){

            Schema::create('codengine_awardbank_posts', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('title');
                $table->text('content');
                $table->dateTime('viewed_at')->nullable();
                $table->string('post_type');
                $table->string('video_url')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_posts');
    }
}