<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards9 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','group_nominations') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->boolean('group_nominations')->default(0);
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_awards','group_nominations')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('group_nominations');
            });
        }
    }
}
