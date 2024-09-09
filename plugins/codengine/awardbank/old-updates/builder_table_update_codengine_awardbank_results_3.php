<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResults3 extends Migration
{
    public function up()
    {

      	if (Schema::hasColumn('codengine_awardbank_results','string') == false) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->string('string')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_results','string')) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->dropColumn('string');
            });
        }
    }
}







