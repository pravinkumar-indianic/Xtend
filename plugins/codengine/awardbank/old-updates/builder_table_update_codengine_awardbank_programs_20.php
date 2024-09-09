<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms20 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','first_billing_sent') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('first_billing_sent')->default(0);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','payment_made') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('payment_made')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','maximum_product_value') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->integer('maximum_product_value')->nullable();

            });
        }

    }
    
    public function down()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','first_billing_sent')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('first_billing_sent');
            });
        }
        if (Schema::hasColumn('codengine_awardbank_programs','payment_made')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('payment_made');
            });
        }
        if (Schema::hasColumn('codengine_awardbank_programs','maximum_product_value')) {        
            Schema::table('codengine_awardbank_programs', function($table)
            {
                $table->dropColumn('maximum_product_value');
            });
        }
    }
}