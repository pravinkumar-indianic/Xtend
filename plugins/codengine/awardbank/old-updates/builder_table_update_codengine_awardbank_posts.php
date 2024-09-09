<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPosts extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_posts','slug') == false) {

            Schema::table('codengine_awardbank_posts', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_posts','slug')) {

            Schema::table('codengine_awardbank_posts', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
