<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers16 extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('users','home_address_id') == false) {

            Schema::table('users', function($table)
            {
                
                $table->integer('home_address_id')->nullable();

            });
        }
        
        if (Schema::hasColumn('users','shipping_address_id') == false) {

            Schema::table('users', function($table)
            {
                
                $table->integer('shipping_address_id')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('users','home_address_id')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('home_address_id')->nullable();
            });
        }

        if (Schema::hasColumn('users','shipping_address_id')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('shipping_address_id')->nullable();
            });
        }

    }
}