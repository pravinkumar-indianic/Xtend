<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankOrders4 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_orders','delivery_json') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->text('delivery_json')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','current_shipping_addr_string') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('current_shipping_addr_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','customer_full_name_string') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('customer_full_name_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','customer_full_email_string') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('customer_full_email_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','customer_full_phone_number_string') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->string('customer_full_phone_number_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','overall_order_status') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->integer('overall_order_status')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_has_vouchers') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->boolean('order_has_vouchers')->default(0);
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_full_redeemed_vouchers') == false) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->boolean('order_full_redeemed_vouchers')->default(0);
            });
        }
    }
    
    public function down()
    {

       if (Schema::hasColumn('codengine_awardbank_orders','delivery_json')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('delivery_json')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','current_shipping_addr_string')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('current_shipping_addr_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','customer_full_name_string')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('customer_full_name_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','customer_full_email_string')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('customer_full_email_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','customer_full_phone_number_string')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('customer_full_phone_number_string')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','overall_order_status')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('overall_order_status')->nullable();
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_has_vouchers')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_has_vouchers');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_orders','order_full_redeemed_vouchers')) {

            Schema::table('codengine_awardbank_orders', function($table)
            {
                $table->dropColumn('order_full_redeemed_vouchers');
            });
        }
    }
}
