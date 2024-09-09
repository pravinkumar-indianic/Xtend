<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankTtRes extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_tt_res', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('targeting_tag_id')->unsigned();
            $table->integer('result_id')->unsigned();
            $table->primary(['targeting_tag_id','result_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_tt_res');
    }
}
