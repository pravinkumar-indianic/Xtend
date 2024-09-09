<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankVotes4 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_votes', function($table)
        {
            $table->integer('award_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_votes', function($table)
        {
            $table->dropColumn('award_id');
        });
    }
}
