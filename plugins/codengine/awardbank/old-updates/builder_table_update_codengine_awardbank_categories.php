<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankCategories extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_categories','slug') == false) {

            Schema::table('codengine_awardbank_categories', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_categories','slug')) {

            Schema::table('codengine_awardbank_categories', function($table)
            {
                //$table->dropColumn('slug');
            });
        }
    }
}
