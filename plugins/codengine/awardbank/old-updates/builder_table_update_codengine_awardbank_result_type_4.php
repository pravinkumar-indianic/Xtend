<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResultType4 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_result_type', function($table)
        {
            $table->string('type', 255)->nullable()->change();
            $table->integer('benchmark')->default(0)->change();
            $table->integer('allocated_points_value')->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_result_type', function($table)
        {
            $table->string('type', 255)->nullable(false)->change();
            $table->integer('benchmark')->default(null)->change();
            $table->integer('allocated_points_value')->default(null)->change();
        });
    }
}
