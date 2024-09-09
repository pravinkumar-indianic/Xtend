<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankNominations extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->text('questions_answers')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->dropColumn('questions_answers');
        });
    }
}
