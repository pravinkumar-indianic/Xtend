<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankBillingInvoice3 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','type') == false) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->string('type')->nullable();
                });
            }

        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_billinginvoice', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billinginvoice','type')) {

                Schema::table('codengine_awardbank_billinginvoice', function($table)
                {
                    $table->dropColumn('type')->nullable();
                });
            }

        });
    }
}
