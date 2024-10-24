<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankTeams4 extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_teams','billingcontact_id') == false) {

            Schema::table('codengine_awardbank_teams', function($table)
            {
                
                $table->integer('billingcontact_id')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_teams','billingcontact_id')) {
            Schema::table('codengine_awardbank_teams', function($table)
            {
                $table->dropColumn('billingcontact_id')->nullable();
            });
        }

    }

}

