<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankNominations3 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->integer('votescount')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->dropColumn('votescount');
        });
    }
}
