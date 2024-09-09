<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers14 extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('users','business_name') == false) {

            Schema::table('users', function($table)
            {
                
                $table->integer('business_name')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('users','business_name')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('business_name')->nullable();
            });
        }

    }

}