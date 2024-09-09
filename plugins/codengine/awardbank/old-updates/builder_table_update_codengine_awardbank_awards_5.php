<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards5 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','featured') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->boolean('featured')->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_awards','featured')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('featured');
            });
        }

    }
}
