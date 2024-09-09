<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards6 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','nomination_type') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->integer('nomination_type')->default(0);
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_awards','nomination_type')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('nomination_type');
            });
        }

    }
}
