<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankVotes3 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_votes','voter_full_name') == false) {

            Schema::table('codengine_awardbank_votes', function($table)
            {
                $table->string('voter_full_name')->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_votes','voter_full_name')) {

            Schema::table('codengine_awardbank_votes', function($table)
            {
                $table->string('voter_full_name')->nullable();
            });
        }
    }
}
