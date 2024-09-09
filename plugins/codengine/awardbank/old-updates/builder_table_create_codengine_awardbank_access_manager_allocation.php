<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankAccessManagerAllocation extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_access_manager_allocation')){

            Schema::create('codengine_awardbank_access_manager_allocation', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('managable_id');
                $table->string('managable_type');
                $table->integer('user_id');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_access_manager_allocation');
    }
}
