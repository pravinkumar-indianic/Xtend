<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankVotes extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_votes', function($table)
        {
            $table->integer('program_id')->nullable();
            $table->text('questions_answers')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_votes', function($table)
        {
            $table->dropColumn('program_id');
            $table->dropColumn('questions_answers');
        });
    }
}
