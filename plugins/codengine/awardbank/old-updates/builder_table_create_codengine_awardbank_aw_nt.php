<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankAwNt extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_aw_nt', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('award_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->primary(['award_id','team_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_aw_nt');
    }
}
