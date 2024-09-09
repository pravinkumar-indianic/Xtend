<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms8 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_programs','xero_job_code_suffix') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('xero_job_code_suffix')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','xero_billing_start_date') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->timestamp('xero_billing_start_date')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','xero_billing_next_date') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->timestamp('xero_billing_next_date')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','xero_renewal_billing_frequency') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->integer('xero_renewal_billing_frequency')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','xero_renewal_day_of_month') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->integer('xero_renewal_day_of_month')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','xero_renewal_price_plan') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->integer('xero_renewal_price_plan')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','xero_renewal_member_count') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->integer('xero_renewal_member_count')->nullable();

            });
        }

    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('xero_job_code_suffix')->nullable();
        });

        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('xero_billing_start_date')->nullable();
        });

        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('xero_billing_next_date')->nullable();
        });


        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('xero_renewal_billing_frequency')->nullable();
        });

        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('xero_renewal_day_of_month')->nullable();
        });

        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('xero_renewal_price_plan')->nullable();
        });

        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('xero_renewal_member_count')->nullable();
        });
    }
}
