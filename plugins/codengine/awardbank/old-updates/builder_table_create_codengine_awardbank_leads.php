<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankLeads extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_leads', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('followed_up')->default(0);
            $table->string('follow_up_by')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_leads');
    }
}
