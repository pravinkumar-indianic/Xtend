<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankTags extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_tags')){

            Schema::create('codengine_awardbank_tags', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('name');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_tags');
    }
}