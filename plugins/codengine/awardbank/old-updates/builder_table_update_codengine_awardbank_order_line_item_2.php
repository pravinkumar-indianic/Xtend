<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrderLineItem2 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_order_line_item','notes') == false) {

            Schema::table('codengine_awardbank_order_line_item', function($table)
            {
                $table->text('notes');
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_order_line_item','notes')) {

            Schema::table('codengine_awardbank_order_line_item', function($table)
            {
                $table->dropColumn('notes');
            });
        }
    }
}
