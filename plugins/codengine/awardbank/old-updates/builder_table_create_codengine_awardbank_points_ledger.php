<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankPointsLedger extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_points_ledger', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->decimal('dollar_value', 10, 0)->default(0);
            $table->integer('type')->default(0);
            $table->integer('user_id')->nullable();
            $table->integer('program_id')->nullable();
            $table->string('xero_id')->default('0');
            $table->integer('points_value')->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_points_ledger');
    }
}
