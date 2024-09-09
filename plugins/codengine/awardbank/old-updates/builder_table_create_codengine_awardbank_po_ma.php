<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPoMa extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_po_ma', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->primary(['post_id','user_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_po_ma');
    }
}
