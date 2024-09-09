<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResults4 extends Migration
{
    public function up()
    {

      	if (Schema::hasColumn('codengine_awardbank_results','attachment_level') == false) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->string('attachment_level')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_results','attachment_level')) {

            Schema::table('codengine_awardbank_results', function($table)
            {
                $table->dropColumn('attachment_level');
            });
        }
    }
}