<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPointallocations2 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_pointallocations','current_allocation') == false) {

            Schema::table('codengine_awardbank_pointallocations', function($table)
            {
                $table->boolean('current_allocation')->default(0);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_pointallocations','current_allocation')) {

            Schema::table('codengine_awardbank_pointallocations', function($table)
            {
                $table->dropColumn('current_allocation');
            });
        }
    }
}
