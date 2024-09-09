<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPointallocations extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_pointallocations','allocator') == false) {

            Schema::table('codengine_awardbank_pointallocations', function($table)
            {

                $table->integer('allocator')->default(1);

            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_pointallocations','allocator')) {

            Schema::table('codengine_awardbank_pointallocations', function($table)
            {
                $table->dropColumn('allocator');
            });
        }
    }
}
