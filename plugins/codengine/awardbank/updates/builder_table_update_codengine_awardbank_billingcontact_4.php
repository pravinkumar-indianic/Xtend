<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankBillingcontact4 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_billingcontact', function($table)
        {
            $table->integer('sort_order')->default(0);
        });
    }

    public function down()
    {
        Schema::table('codengine_awardbank_billingcontact', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
