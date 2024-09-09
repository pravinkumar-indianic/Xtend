<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankRegions6 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_regions','billingcontact_id') == false) {

            Schema::table('codengine_awardbank_regions', function($table)
            {
                
                $table->integer('billingcontact_id')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_regions','billingcontact_id')) {
            Schema::table('codengine_awardbank_regions', function($table)
            {
                $table->dropColumn('billingcontact_id')->nullable();
            });
        }

    }
}
