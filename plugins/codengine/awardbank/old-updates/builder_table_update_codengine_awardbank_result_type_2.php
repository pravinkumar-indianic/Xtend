<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResultType2 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_result_type','program_id') == false) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->integer('program_id')->nullable();
            });
        }

 

        if (Schema::hasColumn('codengine_awardbank_result_type','group_name') == false) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->string('group_name')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_result_type','label') == false) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->string('label')->nullable();
            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_result_type','program_id')) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->dropColumn('program_id');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_result_type','group_name')) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->dropColumn('group_name');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_result_type','label')) {

            Schema::table('codengine_awardbank_result_type', function($table)
            {
                $table->dropColumn('label');
            });
        }
    }
}
