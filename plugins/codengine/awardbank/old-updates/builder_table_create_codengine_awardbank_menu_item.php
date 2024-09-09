<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankMenuItem extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_menu_item')){
        
            Schema::create('codengine_awardbank_menu_item', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('name', 255);
                $table->text('hovertext');
                $table->integer('parent_id')->nullable();
                $table->integer('nest_left')->default(1);
                $table->integer('nest_right')->default(1);
                $table->integer('nest_depth')->default(1);
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_menu_item');
    }
}
