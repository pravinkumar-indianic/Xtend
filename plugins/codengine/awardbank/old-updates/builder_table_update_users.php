<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateUsers extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('users','slug') == false) {

            Schema::table('users', function($table)
            {
                $table->string('slug', 255);
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('users','slug')) {

            Schema::table('users', function($table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
