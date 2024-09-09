<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPermissionEntityAllocation extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_permission_entity_allocation')){

            Schema::create('codengine_awardbank_permission_entity_allocation', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('permissionentityallocatable_id');
                $table->string('permissionentityallocatable_type');
                $table->integer('permission_id');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_permission_entity_allocation');
    }
}