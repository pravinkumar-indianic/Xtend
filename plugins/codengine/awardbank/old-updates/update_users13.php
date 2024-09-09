<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers13 extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('users','billingcontact_id') == false) {

            Schema::table('users', function($table)
            {
                
                $table->integer('billingcontact_id')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('users','billingcontact_id')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('billingcontact_id')->nullable();
            });
        }

    }

}