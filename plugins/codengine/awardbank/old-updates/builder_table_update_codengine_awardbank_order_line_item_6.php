<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrderLineItem6 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->dateTime('last_po_email_sent')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->dropColumn('last_po_email_sent');
        });
    }
}
