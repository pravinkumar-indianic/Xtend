<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPrograms extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('codengine_awardbank_programs')){

            Schema::create('codengine_awardbank_programs', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('primary_color')->default('grey');
                $table->string('secondary_color')->default('grey');
                $table->integer('organization_id')->nullable();
                $table->dateTime('start_date')->nullable();
                $table->dateTime('end_date')->nullable();
                $table->string('points_name')->default('points');
                $table->integer('scale_points_by')->default(1);
                $table->string('program_markup_type')->default('none');
                $table->integer('program_markup_integer')->default();
                $table->integer('address_id')->nullable();
                $table->string('shipping_message')->nullable();
                $table->boolean('shipping_message_status')->default(true);
                $table->boolean('display_results_elite')->default(false);
                $table->json('content_block');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_programs');
    }
}
