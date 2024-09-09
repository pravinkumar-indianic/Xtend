<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankSuppliers3 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','territory') == false) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                $table->text('territory')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','territory')) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                //$table->dropColumn('territory');
            });
        }
    }
}
