<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankProductProgramExclusions extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_p_p_ex')){

            Schema::create('codengine_awardbank_p_p_ex', function($table)
            {
                $table->engine = 'InnoDB';
                $table->integer('program_id')->unsigned();
                $table->integer('product_id')->unsigned();
                $table->primary(['program_id', 'product_id']);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_p_p_ex');
    }
}
