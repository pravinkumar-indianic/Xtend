<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPoLi extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_po_li', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('like_id')->unsigned();
            $table->primary(['post_id','like_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_po_li');
    }
}
