<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankBillingcontact3 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_billingcontact', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billingcontact','taxnumber') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('taxnumber')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','is_supplier') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('is_supplier')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','is_customer') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('is_customer')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','default_currency') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('default_currency')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','sales_default_account_code') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('sales_default_account_code')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','purchase_default_account_code') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('purchase_default_account_code')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','sales_tracking_categories') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('sales_tracking_categories')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','sales_tracking_categories') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('sales_tracking_categories')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','purchase_tracking_categories') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('purchase_tracking_categories')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','tracking_category_name') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('tracking_category_name')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','tracking_option_name') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('tracking_option_name')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','tracking_category_name') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('tracking_category_name')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','payment_terms') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('payment_terms')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','contact_groups') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('contact_groups')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','website') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('website')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','branding_theme') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('branding_theme')->nullable();
                });
            }  
            
            if (Schema::hasColumn('codengine_awardbank_billingcontact','discount') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('discount')->nullable();
                });
            }   

            if (Schema::hasColumn('codengine_awardbank_billingcontact','balances') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('balances')->nullable();
                });
            }    

            if (Schema::hasColumn('codengine_awardbank_billingcontact','payway_customer_id') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('payway_customer_id')->nullable();
                });
            }   

            if (Schema::hasColumn('codengine_awardbank_billingcontact','payway_last_token') == false) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->string('payway_last_token')->nullable();
                });
            }    

        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_billingcontact', function($table)
        {

            if (Schema::hasColumn('codengine_awardbank_billingcontact','taxnumber')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('taxnumber')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','is_supplier')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('is_supplier')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','is_customer')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('is_customer')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','default_currency')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('default_currency')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','sales_default_account_code')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('sales_default_account_code')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','purchase_default_account_code')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('purchase_default_account_code')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','sales_tracking_categories')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('sales_tracking_categories')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','purchase_tracking_categories')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('purchase_tracking_categories')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','tracking_category_name')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('tracking_category_name')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','payment_terms')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('payment_terms')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','contact_groups')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('contact_groups')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','website')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('website')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','branding_theme')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('branding_theme')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','discount')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('discount')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','balances')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('balances')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','payway_customer')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('payway_customer')->nullable();
                });
            }

            if (Schema::hasColumn('codengine_awardbank_billingcontact','payway_last_token')) {

                Schema::table('codengine_awardbank_billingcontact', function($table)
                {
                    $table->dropColumn('payway_last_token')->nullable();
                });
            }

        });
    }
}