<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPointsLedger3 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_points_ledger', function($table)
        {
            $table->integer('team_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_points_ledger', function($table)
        {
            $table->dropColumn('team_id');
        });
    }
}
