<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankRegions7 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_regions', function($table)
        {
            $table->boolean('team_converted')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_regions', function($table)
        {
            $table->dropColumn('team_converted');
        });
    }
}
