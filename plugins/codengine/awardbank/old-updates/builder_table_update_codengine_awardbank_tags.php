<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankTags extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_tags','slug') == false) {

            Schema::table('codengine_awardbank_tags', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_tags','slug')) {

            Schema::table('codengine_awardbank_tags', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
