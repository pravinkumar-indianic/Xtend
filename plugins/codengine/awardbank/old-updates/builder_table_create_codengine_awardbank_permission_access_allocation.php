<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPermissionAccessAllocation extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_permission_access_allocation')){

            Schema::create('codengine_awardbank_permission_access_allocation', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('permission_id');
                $table->string('permissionaccessallocatable_type');
                $table->integer('permissionaccessallocatable_id');
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_permission_access_allocation');
    }
}