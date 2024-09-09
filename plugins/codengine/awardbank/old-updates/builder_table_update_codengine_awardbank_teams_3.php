<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankTeams3 extends Migration
{

    public function up()
    {
        Schema::table('codengine_awardbank_teams', function($table)
        {
            $table->integer('points_limit')->nullable()->unsigned(false)->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_teams', function($table)
        {
            $table->boolean('points_limit')->nullable()->unsigned(false)->default(null)->change();
        });
    }

}

