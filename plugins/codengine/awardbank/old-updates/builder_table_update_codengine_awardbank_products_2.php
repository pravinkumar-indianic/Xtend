<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankProducts2 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_products','completeness_score') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->integer('completeness_score')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','completeness_array') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->text('completeness_array')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','image_valid') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->boolean('image_valid')->default(0);
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','deliver_base') == false) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                $table->boolean('deliver_base')->default(0);
            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_products','completeness_score')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('completeness_score')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','completeness_array')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('completeness_array');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','image_valid')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('image_valid');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_products','deliver_base')) {

            Schema::table('codengine_awardbank_products', function($table)
            {
                //$table->dropColumn('deliver_base')->default(0);
            });
        }
    }
}
