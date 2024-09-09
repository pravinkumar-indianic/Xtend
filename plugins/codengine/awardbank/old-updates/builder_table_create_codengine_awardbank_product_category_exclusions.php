<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankProductCategoryExclusions extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_p_c_ex')){

            Schema::create('codengine_awardbank_p_c_ex', function($table)
            {
                $table->engine = 'InnoDB';
                $table->integer('category_id')->unsigned();
                $table->integer('program_id')->unsigned();
                $table->primary(['program_id', 'category_id']);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_p_c_ex');
    }
}