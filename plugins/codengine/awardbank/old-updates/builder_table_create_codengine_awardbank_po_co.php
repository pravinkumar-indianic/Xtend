<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPoCo extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_po_co', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('comment_id')->unsigned();
            $table->primary(['post_id','comment_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_po_co');
    }
}
