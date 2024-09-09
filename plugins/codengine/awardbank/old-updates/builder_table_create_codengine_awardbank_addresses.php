<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankAddresses extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_addresses')){

            Schema::create('codengine_awardbank_addresses', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('country_id');
                $table->string('state')->nullable();
                $table->string('city');
                $table->string('suburb_name');
                $table->integer('unit_number')->nullable();
                $table->integer('street_number');
                $table->string('street_name');
                $table->text('full_address');
                $table->integer('postcode')->nullable();
                $table->float('lat')->nullable();
                $table->float('lng')->nullable();
                $table->string('type', 10)->nullable();
                $table->string('map')->nullable();
                $table->string('phone_number')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_addresses');
    }
}
