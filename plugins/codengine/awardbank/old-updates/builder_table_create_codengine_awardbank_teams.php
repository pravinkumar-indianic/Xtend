<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankTeams extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_teams')){

            Schema::create('codengine_awardbank_teams', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('primary_color')->default('grey');
                $table->string('secondary_color')->default('grey');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_teams');
    }
}