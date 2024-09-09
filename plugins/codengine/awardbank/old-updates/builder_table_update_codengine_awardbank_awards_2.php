<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards2 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','slug') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','slug')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
