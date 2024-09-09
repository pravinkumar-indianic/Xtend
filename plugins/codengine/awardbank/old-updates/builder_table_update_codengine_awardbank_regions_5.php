<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankRegions5 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_regions', function($table)
        {
            $table->integer('points_limit')->nullable()->unsigned(false)->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_regions', function($table)
        {
            $table->dropColumn('points_limit');
            
        });
    }
}
