<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankUTm extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_u_tm', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->primary(['user_id','team_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_u_tm');
    }
}
