<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankProducts7 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_products','small_font_description') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
            	$table->text('small_font_description')->nullable();
            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_products','small_font_description')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
            	$table->dropColumn('small_font_description');
            });
        }
    }
}
