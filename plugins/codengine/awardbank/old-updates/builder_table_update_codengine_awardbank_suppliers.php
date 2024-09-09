<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankSuppliers extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','shipping_rate_id') == false) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                $table->integer('shipping_rate_id')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','shipping_rate_id')) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                //$table->dropColumn('shipping_rate_id');
            });
        }
    }
}
