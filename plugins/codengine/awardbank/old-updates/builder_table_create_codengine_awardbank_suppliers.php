<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankSuppliers extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_suppliers')){

            Schema::create('codengine_awardbank_suppliers', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('mark_up_integer')->nullable();  
                $table->string('mark_up_type')->nullable();
                $table->string('primary_color')->default('grey');

                $table->integer('courier_id')->nullable();
                $table->string('name');
                $table->text('description');
                $table->integer('address_id')->nullable();
                $table->string('primary_contact_name')->nullable();  
                $table->string('secondary_contact_name')->nullable();                            
                $table->string('primary_contact_number')->nullable();     
                $table->string('secondary_contact_number')->nullable();   
                $table->string('primary_contact_email')->nullable();     
                $table->string('secondary_contact_email')->nullable();    

                $table->string('territory')->nullable();
                                           
            });
        }
    }
    
    public function down()
    {
        // REMOVE DOWN TO PRESERVE PRODUCTION TABLE
    }
}