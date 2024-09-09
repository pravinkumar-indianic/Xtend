<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders5 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_id') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->integer('order_program_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_name') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_program_name')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_address') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_program_address')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_image_url') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_program_image_url')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_points_name') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_program_points_name')->nullable();
            });
        }


        if (Schema::hasColumn('codengine_awardbank_orders','order_program_points_multiple_type') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_program_points_multiple_type')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_points_multiple_integer') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('order_program_points_multiple_integer')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_region_id') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->integer('order_region_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_team_id') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->integer('order_team_id')->nullable();
            });
        } 
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_orders','order_program_id')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_program_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_name')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_program_name')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_address')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_program_address')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_image_url')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_program_image_url')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_points_name')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_program_points_name')->nullable();
            });
        }


        if (Schema::hasColumn('codengine_awardbank_orders','order_program_points_multiple_type')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_program_points_multiple_type')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_program_points_multiple_integer')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_program_points_multiple_integer')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_region_id')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_region_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_team_id')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_team_id')->nullable();
            });
        } 
 
    }
}
