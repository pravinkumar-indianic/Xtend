<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankAwardAwardPaired extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_a_a_paired')){

            Schema::create('codengine_awardbank_a_a_paired', function($table)
            {
                $table->engine = 'InnoDB';
                $table->integer('award_id1')->unsigned();
                $table->integer('award_id2')->unsigned();
                $table->primary(['award_id1', 'award_id2']);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_a_a_paired');
    }
}
