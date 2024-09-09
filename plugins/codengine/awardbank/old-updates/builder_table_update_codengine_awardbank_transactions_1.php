<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankTransactions1 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_transactions','type') == false) {

            Schema::table('codengine_awardbank_transactions', function($table)
            {
                $table->string('type')->default('transaction');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_transactions','program_id') == false) {

            Schema::table('codengine_awardbank_transactions', function($table)
            {
                $table->integer('program_id')->nullable();
            });
        }
        
        if (Schema::hasColumn('codengine_awardbank_transactions','billing_contact_id') == false) {

            Schema::table('codengine_awardbank_transactions', function($table)
            {
                $table->integer('billing_contact_id')->nullable();
            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_transactions','type')) {

            Schema::table('codengine_awardbank_transactions', function($table)
            {
                $table->dropColumn('y')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_transactions','program_id')) {

            Schema::table('codengine_awardbank_transactions', function($table)
            {
                $table->dropColumn('program_id')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_transactions','billing_contact_id')) {

            Schema::table('codengine_awardbank_transactions', function($table)
            {
                $table->dropColumn('billing_contact_id')->nullable();
            });
        }
    }
}
