<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms6 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->string('territory')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('territory');
        });
    }
}
