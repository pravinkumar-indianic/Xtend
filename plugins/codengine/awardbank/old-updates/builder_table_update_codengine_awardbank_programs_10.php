<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms10 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_rewards') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_rewards')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_posts') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_posts')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_awards') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_awards')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_users') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_users')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_results') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_results')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_admin_reports') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_admin_reports')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_activity_feed') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_activity_feed')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_program_tools') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_program_tools')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_user_profile') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('module_allow_user_profile')->default(1);

            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_rewards')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_rewards');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_posts')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_posts');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_awards')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_awards');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_users')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_users');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_results')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_results');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_admin_reports')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_admin_reports');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_activity_feed')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_activity_feed');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_program_tools')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_program_tools');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','module_allow_user_profile')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('module_allow_user_profile');
            });
        }
    }
}
