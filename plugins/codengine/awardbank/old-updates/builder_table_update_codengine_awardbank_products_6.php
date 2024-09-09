<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankProducts6 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_products','activate_after') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
            	$table->timestamp('activate_after')->nullable();
            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_products','activate_after')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
            	//$table->timestamp('activate_after')->nullable();
            });
        }
    }
}
