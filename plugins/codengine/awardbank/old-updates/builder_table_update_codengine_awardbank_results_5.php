<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResults5 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_results', function($table)
        {
            $table->integer('month_index')->default(0);
            $table->integer('year_index')->default(19);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_results', function($table)
        {
            $table->dropColumn('month_index');
            $table->dropColumn('year_index');
        });
    }
}
