<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankBillingcontact2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_billingcontact', function($table)
        {
            $table->integer('program_id')->nullable();
            $table->integer('organization_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_billingcontact', function($table)
        {
            $table->dropColumn('program_id');
            $table->dropColumn('organization_id');
        });
    }
}
