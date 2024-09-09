<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankTR extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_t_r')){

            Schema::create('codengine_awardbank_t_r', function($table)
            {
                $table->engine = 'InnoDB';
                $table->integer('team_id')->unsigned();
                $table->integer('region_id')->unsigned();
                $table->primary(['team_id', 'region_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_t_r');
    }
}