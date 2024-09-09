<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankAwMa extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_aw_ma', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('award_id')->unsigned();
            $table->integer('manager_id')->unsigned();
            $table->primary(['award_id','manager_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_aw_ma');
    }
}
