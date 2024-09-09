<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards8 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','program_id') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->integer('program_id')->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_awards','program_id')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->integer('program_id');
            });
        }
    }
}
