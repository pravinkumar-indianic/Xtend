<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','slug') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','slug')) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
