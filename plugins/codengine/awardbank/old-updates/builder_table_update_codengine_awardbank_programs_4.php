<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms4 extends Migration
{

    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','subdomain') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('subdomain')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','dashboard') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('dashboard')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','rewards_homepage') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('rewards_homepage')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','rewards_category_list') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('rewards_category_list')->nullable();

            });
        }

         if (Schema::hasColumn('codengine_awardbank_programs','rewards_detail') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('rewards_detail')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','posts_homepage') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('posts_homepage')->nullable();

            });
        }  

        if (Schema::hasColumn('codengine_awardbank_programs','posts_category_list') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('posts_category_list')->nullable();

            });
        }   

        if (Schema::hasColumn('codengine_awardbank_programs','posts_detail') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('posts_detail')->nullable();

            });
        }    

        if (Schema::hasColumn('codengine_awardbank_programs','groups_homepage') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('groups_homepage')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','groups_list') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('groups_list')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','groups_detail') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('groups_detail')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','results_homepage') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('results_homepage')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','results_type_list') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('results_type_list')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','results_detail') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('results_detail')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','awards_list') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('awards_list')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','awards_detail') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('awards_detail')->nullable();

            });
        }

    }

    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_programs','subdomain') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('subdomain')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','dashboard') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('dashboard')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','rewards_homepage') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('rewards_homepage')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','rewards_category_list') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('rewards_category_list')->nullable();

            });
        }

         if (Schema::hasColumn('codengine_awardbank_programs','rewards_detail') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('rewards_detail')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','posts_homepage') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('posts_homepage')->nullable();

            });
        }  

        if (Schema::hasColumn('codengine_awardbank_programs','posts_category_list') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('posts_category_list')->nullable();

            });
        }   

        if (Schema::hasColumn('codengine_awardbank_programs','posts_detail') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('posts_detail')->nullable();

            });
        }    

        if (Schema::hasColumn('codengine_awardbank_programs','groups_homepage') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('groups_homepage')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','groups_list') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('groups_list')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','groups_detail') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('groups_detail')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','results_homepage') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('results_homepage')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','results_type_list') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('results_type_list')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','results_detail') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('results_detail')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','awards_list') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('awards_list')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','awards_detail') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('awards_detail')->nullable();

            });
        }
    }
}