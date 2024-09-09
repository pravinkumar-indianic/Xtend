<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrganizations extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_organizations','slug') == false) {

            Schema::table('codengine_awardbank_organizations', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_organizations','slug')) {

            Schema::table('codengine_awardbank_organizations', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
