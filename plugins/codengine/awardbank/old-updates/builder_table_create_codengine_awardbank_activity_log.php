<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankActivityLog extends Migration
{
    public function up()
    {
        if(!Schema::hasTable('codengine_awardbank_activity_log')){

            Schema::create('codengine_awardbank_activity_log', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('ref_id', 50);
                $table->string('activity_type');
                $table->string('activity_description', 255)->nullable();
                $table->integer('value');
                $table->integer('user_id');
                $table->string('ip_address', 50);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_activity_log');
    }
}
