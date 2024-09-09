<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPermissions extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_permissions')){

            Schema::create('codengine_awardbank_permissions', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('type');
                $table->boolean('active')->default(true);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_permissions');
    }
}