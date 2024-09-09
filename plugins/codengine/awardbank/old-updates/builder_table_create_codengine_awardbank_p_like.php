<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPLike extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_p_like')){

            Schema::create('codengine_awardbank_p_like', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('like_id');
                $table->integer('likeable_id');
                $table->string('likeable_type');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_p_like');
    }
}
