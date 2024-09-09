<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers17 extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('users','today_birth_date') == false) {

            Schema::table('users', function($table)
            {
                
                $table->boolean('today_birth_date')->default(0);

            });
        }
    
        if (Schema::hasColumn('users','updated_birth_date') == false) {

            Schema::table('users', function($table)
            {
                
                $table->timestamp('updated_birth_date')->nullable();

            });
        }
        
        if (Schema::hasColumn('users','today_commencement_date') == false) {

            Schema::table('users', function($table)
            {
                
                $table->boolean('today_commencement_date')->default(0);

            });
        }
        
        if (Schema::hasColumn('users','updated_commencement_date') == false) {

            Schema::table('users', function($table)
            {
                
                $table->timestamp('updated_commencement_date')->nullable();

            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('users','today_birth_date')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('today_birth_date')->nullable();
            });
        }

        if (Schema::hasColumn('users','updated_birth_date')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('updated_birth_date')->nullable();
            });
        }


        if (Schema::hasColumn('users','today_commencement_date')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('today_commencement_date')->nullable();
            });
        }

        if (Schema::hasColumn('users','updated_commencement_date')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('updated_commencement_date')->nullable();
            });
        }
    }
}