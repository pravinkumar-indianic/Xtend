<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPointsLedger extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_points_ledger', function($table)
        {
            $table->integer('order_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_points_ledger', function($table)
        {
            $table->dropColumn('order_id');
        });
    }
}
