<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankNominations6 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_nominations','region_id') == false) {

            Schema::table('codengine_awardbank_nominations', function($table)
            {
                $table->integer('region_id')->nullable();
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_nominations','region_id')) {

            Schema::table('codengine_awardbank_nominations', function($table)
            {
                $table->dropColumn('region_id');
            });
        }
    }
}
