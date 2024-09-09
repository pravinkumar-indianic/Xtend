<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrizes5 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_prizes', function($table)
        {
            $table->dropColumn('winner_id');
            $table->dropColumn('userwinner_id');
            $table->dropColumn('teamwinner_id');
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_prizes', function($table)
        {
            $table->integer('winner_id')->nullable();
            $table->integer('userwinner_id')->nullable();
            $table->integer('teamwinner_id')->nullable();
        });
    }
}
