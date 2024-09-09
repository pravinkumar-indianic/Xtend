<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankCategoryAllocation extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_category_allocation')){

            Schema::create('codengine_awardbank_category_allocation', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('entity_id');
                $table->string('entity_type');
                $table->integer('category_id');      
            });
        }
    }
    
    public function down()
    {
        // REMOVE DOWN TO PRESERVE PRODUCTION TABLE    
    }
}