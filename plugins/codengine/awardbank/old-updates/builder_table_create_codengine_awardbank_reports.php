<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankResults extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_reports', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('type', 50);
            $table->integer('program_id')->unsigned();
            $table->date('date_from');
            $table->date('date_to');
            $table->integer('user_id')->unsigned();
            $table->string('filename');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_reports');
    }
}
