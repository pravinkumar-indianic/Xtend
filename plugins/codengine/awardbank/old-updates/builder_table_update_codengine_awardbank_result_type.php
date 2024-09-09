<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResultType extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_result_type','name') == false) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->string('name', 50)->nullable();
            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_result_type','name')) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->dropColumn('name');
            });
        }
    }
}
