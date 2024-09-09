<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPrTw extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_pr_tw', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('prize_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->primary(['prize_id','team_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_pr_tw');
    }
}
