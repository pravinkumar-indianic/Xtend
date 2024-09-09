<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankResults extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_results')){

            Schema::create('codengine_awardbank_results', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('value');
                $table->integer('resulttype_id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_results');
    }
}
