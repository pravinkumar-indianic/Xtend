<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPoPr extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_po_pr', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('program_id')->unsigned();
            $table->primary(['post_id','program_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_po_pr');
    }
}
