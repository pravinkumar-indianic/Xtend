<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankUP extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_u_p', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('program_id')->unsigned();
            $table->primary(['user_id','program_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_u_p');
    }
}
