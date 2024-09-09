<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrganizations3 extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_organizations','billingcontact_id') == false) {

            Schema::table('codengine_awardbank_organizations', function($table)
            {
                
                $table->integer('billingcontact_id')->nullable();

            });
        }

    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_organizations', function($table)
        {
            $table->dropColumn('billingcontact_id')->nullable();
        });

    }

}