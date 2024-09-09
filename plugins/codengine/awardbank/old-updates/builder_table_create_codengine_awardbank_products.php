<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankProducts extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_products')){

            Schema::create('codengine_awardbank_products', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('supplier_id')->nullable();
                $table->string('name');
                $table->string('model_number')->nullable();  
                $table->string('brand')->nullable();      
                $table->string('category-string')->nullable();   
                $table->boolean('category-string-check')->default(true);                              
                $table->longText('description')->nullable();          
                $table->string('primary_color')->default('grey');
                $table->string('cost_currency')->default('AUD');            
                $table->float('cost_ex')->default(0);
                $table->float('cost_tax_percent')->default(10);
                $table->float('cost_freight')->default(0);
                $table->float('cost_gross')->nullable();                
                $table->float('cost_rrp')->default(0);                      
                $table->float('cost_final_cost')->default(0);
                $table->string('points_source')->nullable();              
                $table->integer('points_value')->default(0);      
                $table->float('points_margin')->default(0);                                       
                $table->dateTime('deactivated_at')->nullable();
                $table->integer('replacement_id')->nullable();
                $table->boolean('mark_up_override')->nullable();              
                $table->integer('mark_up_integer')->nullable();  
                $table->string('mark_up_type')->nullable();     
                $table->string('external_image_url')->nullable();    
                $table->integer('active')->default(0);     
                $table->integer('featured')->default(0); 
                $table->integer('length')->default(0);
                $table->integer('width')->default(0); 
                $table->integer('height')->default(0);   
                $table->integer('weight')->default(0);

                $table->string('territory')->nullable();                                                                     
            });
        }
    }
    
    public function down()
    {
        // REMOVE DOWN TO PRESERVE PRODUCTION TABLE
    }
}