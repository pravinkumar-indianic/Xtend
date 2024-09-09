<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankSuppliers4 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','orders_email') == false) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                $table->string('orders_email')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_suppliers','cc_primary') == false) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                $table->boolean('cc_primary')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_suppliers','cc_secondary') == false) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                $table->boolean('cc_secondary')->nullable();
            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','orders_email')) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                //$table->dropColumn('orders_email')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_suppliers','cc_primary')) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                //$table->dropColumn('cc_primary')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_suppliers','cc_secondary')) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                //$table->dropColumn('cc_secondary')->nullable();
            });
        }
    }
}
