<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankTeams extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_teams','slug') == false) {

            Schema::table('codengine_awardbank_teams', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_teams','slug')) {

            Schema::table('codengine_awardbank_teams', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
