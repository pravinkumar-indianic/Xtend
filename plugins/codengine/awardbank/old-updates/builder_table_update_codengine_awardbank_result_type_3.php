<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResultType3 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_result_type','result_group_id') == false) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->integer('resultgroup_id')->nullable();
            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_result_type','result_group_id')) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->dropColumn('resultgroup_id');
            });
        }
    }
}
