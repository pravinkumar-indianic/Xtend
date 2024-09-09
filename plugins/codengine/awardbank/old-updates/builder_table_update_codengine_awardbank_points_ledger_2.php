<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPointsLedger2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_points_ledger', function($table)
        {
            $table->integer('product_id')->nullable();
            $table->integer('transaction_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_points_ledger', function($table)
        {
            $table->dropColumn('product_id');
            $table->dropColumn('transaction_id');
        });
    }
}
