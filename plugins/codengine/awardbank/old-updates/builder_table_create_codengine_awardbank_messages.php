<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankMessages extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('sender_id')->nullable();
            $table->string('sender_fullname')->nullable();
            $table->string('sender_thumb_path')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->string('receiver_fullname')->nullable();
            $table->string('receiver_thumb_path')->nullable();
            $table->text('message_text')->nullable();
            $table->integer('program_id')->nullable();
            $table->string('program_name')->nullable();
            $table->string('program_points_name')->nullable();
            $table->string('program_image_path')->nullable();
            $table->string('program_points_multiple_type')->nullable();
            $table->integer('program_points_multiple_integer')->nullable();
            
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_messages');
    }
}