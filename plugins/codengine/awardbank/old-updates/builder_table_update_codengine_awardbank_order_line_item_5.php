<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrderLineItem5 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->decimal('product_invoiced_amount', 10, 2)->default(0);
            $table->decimal('invoice_po_variance', 10, 2)->default(0);
            $table->decimal('product_dollar_value', 10, 2)->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_order_line_item', function($table)
        {
            $table->dropColumn('product_invoiced_amount');
            $table->dropColumn('invoice_po_variance');
            $table->integer('product_dollar_value')->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
