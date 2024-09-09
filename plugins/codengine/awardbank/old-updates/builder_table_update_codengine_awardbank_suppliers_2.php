<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankSuppliers2 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','slug') == false) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_suppliers','slug')) {

            Schema::table('codengine_awardbank_suppliers', function($table)
            {
                //$table->dropColumn('slug');
            });
        }
    }
}
