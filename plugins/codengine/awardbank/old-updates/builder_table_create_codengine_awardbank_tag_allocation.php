<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankTagAllocation extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_tag_allocation')){

            Schema::create('codengine_awardbank_tag_allocation', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('entity_id');
                $table->string('entity_type');
                $table->integer('tag_id');      
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_p_pt');
        Schema::dropIfExists('codengine_awardbank_tag_allocation');
    }
}