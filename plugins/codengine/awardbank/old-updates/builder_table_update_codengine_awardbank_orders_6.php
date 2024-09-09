<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders6 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_orders', function($table)
        {
            $table->integer('program_id')->nullable();
            $table->decimal('dollar_value', 10, 2)->nullable();
            $table->decimal('points_value', 10, 2)->nullable()->unsigned(false)->default(null)->change();
            $table->decimal('points_value_delivered', 10, 2)->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_orders', function($table)
        {
            $table->dropColumn('program_id');
            $table->dropColumn('dollar_value');
            $table->integer('points_value')->nullable()->unsigned(false)->default(null)->change();
            $table->integer('points_value_delivered')->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
