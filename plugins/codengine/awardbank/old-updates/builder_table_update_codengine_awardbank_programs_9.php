<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms9 extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('codengine_awardbank_programs','billingcontact_id') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->integer('billingcontact_id')->nullable();

            });
        }

    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('billingcontact_id')->nullable();
        });

    }
}
