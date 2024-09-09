<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankBillingInvoice2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','billing_contact_id') == false) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->integer('billing_contact_id')->nullable();
                });
            }

        });

        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','transaction_id') == false) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->integer('transaction_id')->nullable();
                });
            }

        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','billing_contact_id')) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->dropColumn('billing_contact_id')->nullable();
                });
            }

        });

        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','transaction_id')) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->dropColumn('transaction_id')->nullable();
                });
            }

        });
    }
}
