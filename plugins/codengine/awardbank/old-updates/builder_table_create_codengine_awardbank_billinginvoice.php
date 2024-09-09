<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankBillinginvoice extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_billinginvoice', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('xero_id')->nullable();
            $table->string('xero_type')->nullable();
            $table->integer('xero_contact')->nullable();
            $table->dateTime('xero_date')->nullable();
            $table->dateTime('xero_duedate')->nullable();
            $table->string('xero_status')->nullable();
            $table->string('xero_lineamounttypes')->nullable();
            $table->decimal('xero_subtotal', 10, 0)->nullable();
            $table->decimal('xero_totaltax', 10, 0)->nullable();
            $table->decimal('xero_total', 10, 0)->nullable();
            $table->dateTime('xero_totaldiscount')->nullable();
            $table->dateTime('xero_updateddateutc')->nullable();
            $table->string('xero_currencycode')->nullable();
            $table->string('xero_invoiceid')->nullable();
            $table->decimal('xero_currencyrate', 10, 0)->nullable();
            $table->string('xero_invoicenumber')->nullable();
            $table->string('xero_reference')->nullable();
            $table->string('xero_brandingthemeid')->nullable();
            $table->string('xero_url')->nullable();
            $table->boolean('xero_sendtocontact')->nullable();
            $table->dateTime('xero_expectedpaymentdate')->nullable();
            $table->dateTime('xero_plannedpaymentdate')->nullable();
            $table->boolean('xero_hasattachments')->nullable();
            $table->string('xero_payments')->nullable();
            $table->string('xero_overpayments')->nullable();
            $table->decimal('xero_amountdue', 10, 0)->nullable();
            $table->decimal('xero_amountpaid', 10, 0)->nullable();
            $table->dateTime('xero_fullypaidondate')->nullable();
            $table->decimal('xero_amountcredited', 10, 0)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_billinginvoice');
    }
}
