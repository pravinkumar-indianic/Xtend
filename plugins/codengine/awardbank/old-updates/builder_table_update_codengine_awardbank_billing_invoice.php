<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankBillingInvoice extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','program_id') == false) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->integer('program_id')->nullable();
                });
            }

        });

        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','user_id') == false) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->integer('user_id')->nullable();
                });
            }

        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','program_id')) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->dropColumn('program_id')->nullable();
                });
            }

        });

        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','user_id')) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->dropColumn('user_id')->nullable();
                });
            }

        });
    }
}