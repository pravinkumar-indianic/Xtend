<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankBillingcontact extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_billingcontact', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('xero_id');
            $table->integer('accountnumber')->nullable();
            $table->string('contactstatus')->nullable();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('emailaddress')->nullable();
            $table->string('defaultcurrent')->nullable();
            $table->string('trackingcategoryname')->nullable();
            $table->string('trackingcategoryoptions')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_billingcontact');
    }
}
