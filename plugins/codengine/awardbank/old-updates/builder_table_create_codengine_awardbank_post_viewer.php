<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPostViewer extends Migration
{
    public function up()
    {
        
        if(!Schema::hasTable('codengine_awardbank_post_viewer')){

            Schema::create('codengine_awardbank_post_viewer', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('post_id');
                $table->integer('user_id');
                $table->dateTime('viewed_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_post_viewer');
    }
}
