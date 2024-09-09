<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','user_id') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->integer('user_id');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','name')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                    $table->dropColumn('name');
            });
        }
    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','user_id')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','name') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('name', 255);
            });
        }
    }
}
