<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers15 extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('users','points_json') == false) {

            Schema::table('users', function($table)
            {
                
                $table->text('points_json')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('users','points_json')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('points_json')->nullable();
            });
        }

    }

}