<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankCategories2 extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_categories')){
            
            Schema::create('codengine_awardbank_categories', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('name');
                $table->string('type');
                $table->text('description');
                $table->string('main_color');
                $table->integer('product_count')->nullable();
                $table->integer('child_product_count')->nullable();
                $table->integer('parent_id')->nullable();
                $table->integer('nest_left')->nullable();
                $table->integer('nest_right')->nullable();
                $table->integer('nest_depth')->nullable();           
            });
        }
    }
    
    public function down()
    {
        // REMOVE DOWN TO PRESERVE PRODUCTION TABLE
    }
}
