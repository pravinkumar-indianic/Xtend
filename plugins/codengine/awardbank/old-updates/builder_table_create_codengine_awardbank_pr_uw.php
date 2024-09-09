<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPrUw extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_pr_uw', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('prize_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->primary(['prize_id','user_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_pr_uw');
    }
}
