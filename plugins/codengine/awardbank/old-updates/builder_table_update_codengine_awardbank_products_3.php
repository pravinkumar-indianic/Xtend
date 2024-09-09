<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankProducts3 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_products','supplier_name') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->string('supplier_name')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','category_array') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->text('category_array')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','tag_array') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->text('tag_array')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','owner_array') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->text('owner_array')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','viewability_array') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->text('viewability_array')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_products','supplier_name') ) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('supplier_name')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','category_array') ) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('category_array')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','tag_array') ) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('tag_array')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','owner_array') ) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('owner_array')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','viewability_array') ) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('viewability_array')->nullable();
            });
        }
    }
}
