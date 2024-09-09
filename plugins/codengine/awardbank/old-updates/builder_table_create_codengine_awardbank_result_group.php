<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankResultGroup extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_result_group')){

            Schema::create('codengine_awardbank_result_group', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name');
                $table->string('type');
                $table->string('label');
                $table->text('description');
                $table->integer('program_id');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_result_group');
    }
}
