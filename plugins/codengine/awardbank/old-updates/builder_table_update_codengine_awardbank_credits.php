<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankCredits extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_credits','name') == false ) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->string('name', 255)->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','external_reference') == false) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->string('external_reference')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','allocated_points') == false) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->integer('allocated_points')->nullable();;
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','unallocated_points') == false) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->integer('unallocated_points')->nullable();;
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','spent_points') == false) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->integer('spent_points')->nullable();;
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','program_id') == false) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->integer('program_id')->nullable();;
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_credits','name')) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->dropColumn('name')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','external_reference')) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->dropColumn('external_reference')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','allocated_points')) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->dropColumn('allocated_points')->nullable();;
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','unallocated_points')) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->dropColumn('unallocated_points')->nullable();;
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','spent_points')) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->dropColumn('spent_points')->nullable();;
            });
        }

        if (Schema::hasColumn('codengine_awardbank_credits','program_id') == false) {

            Schema::table('codengine_awardbank_credits', function($table)
            {
                $table->integer('program_id')->nullable();;
            });
        }
    }
}
