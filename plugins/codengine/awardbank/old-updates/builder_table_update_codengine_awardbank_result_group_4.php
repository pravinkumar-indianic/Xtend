<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResultGroup4 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_result_group', function($table)
        {
            $table->boolean('display_dashboard')->default(1);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_result_group', function($table)
        {
            $table->dropColumn('display_dashboard');
        });
    }
}
