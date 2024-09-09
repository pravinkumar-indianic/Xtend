<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResults6 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_results', function($table)
        {
            $table->integer('row')->nullable()->default(1);
            $table->string('label', 50)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_results', function($table)
        {
            $table->dropColumn('row');
            $table->string('label', 50)->default(null)->change();
        });
    }
}
