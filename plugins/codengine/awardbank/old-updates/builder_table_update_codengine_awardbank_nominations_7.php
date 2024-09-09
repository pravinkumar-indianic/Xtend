<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankNominations7 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_nominations','submit_team_id') == false) {

            Schema::table('codengine_awardbank_nominations', function($table)
            {
                $table->integer('submit_team_id')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_nominations','submit_team_id')) {

            Schema::table('codengine_awardbank_nominations', function($table)
            {
                $table->dropColumn('submit_team_id');
            });
        }
    }
}
