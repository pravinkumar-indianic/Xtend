<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankUT extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_u_t')){

            Schema::create('codengine_awardbank_u_t', function($table)
            {
                $table->engine = 'InnoDB';
                $table->integer('user_id')->unsigned();
                $table->integer('team_id')->unsigned();
                $table->primary(['user_id', 'team_id']);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_u_t');
    }
}