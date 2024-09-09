<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankNominations2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->string('nominee_full_name')->nullable();
            $table->string('created_full_name')->nullable();
            $table->integer('program_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->dropColumn('nominee_full_name');
            $table->dropColumn('created_full_name');
            $table->dropColumn('program_id');
        });
    }
}
