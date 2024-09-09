<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankProducts extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_products','slug') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_products','slug')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('slug');
            });
        }
    }
}
