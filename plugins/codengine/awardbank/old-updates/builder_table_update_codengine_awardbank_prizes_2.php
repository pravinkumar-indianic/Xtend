<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrizes2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_prizes', function($table)
        {
            $table->integer('userwinner_id')->nullable();
            $table->integer('teamwinner_id')->nullable();
            $table->boolean('iswon')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_prizes', function($table)
        {
            $table->dropColumn('userwinner_id');
            $table->dropColumn('teamwinner_id');
            $table->dropColumn('iswon');
        });
    }
}
