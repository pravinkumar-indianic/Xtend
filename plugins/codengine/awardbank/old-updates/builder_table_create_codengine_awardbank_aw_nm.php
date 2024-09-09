<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankAwNm extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_aw_nm', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('award_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->primary(['award_id','user_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_aw_nm');
    }
}
