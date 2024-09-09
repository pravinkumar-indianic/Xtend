<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPoTe extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_po_te', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->primary(['post_id','team_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_po_te');
    }
}
