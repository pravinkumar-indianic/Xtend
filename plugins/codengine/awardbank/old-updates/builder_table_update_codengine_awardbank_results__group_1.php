<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResultsGroup1 extends Migration
{
    public function up()
    {


      	if (Schema::hasColumn('codengine_awardbank_result_group','created_at') == false) {

            Schema::table('codengine_awardbank_result_group', function($table)
            {
                $table->integer('created_at')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_result_group','updated_at') == false) {

            Schema::table('codengine_awardbank_result_group', function($table)
            {
                $table->integer('updated_at')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_result_group','deleted_at') == false) {

            Schema::table('codengine_awardbank_result_group', function($table)
            {
                $table->integer('deleted_at')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_result_group','created_at')) {

            Schema::table('codengine_awardbank_result_group', function($table)
            {
                $table->dropColumn('created_at');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_result_group','updated_at')) {

            Schema::table('codengine_awardbank_result_group', function($table)
            {
                $table->dropColumn('updated_at');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_result_group','deleted_at')) {

            Schema::table('codengine_awardbank_result_group', function($table)
            {
                $table->dropColumn('deleted_at');
            });
        }

    }
}






