<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAddresses extends Migration
{
    public function up()
    {


        if (Schema::hasColumn('codengine_awardbank_addresses','business_name') == false) {
       
            Schema::table('codengine_awardbank_addresses', function($table)
            {
                $table->string('business_name')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_addresses','attn_name') == false) {
       
            Schema::table('codengine_awardbank_addresses', function($table)
            {
                $table->string('attn_name')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_addresses','floor') == false) {
       
            Schema::table('codengine_awardbank_addresses', function($table)
            {
                $table->string('floor')->nullable();

            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_addresses','business_name')) {
       
            Schema::table('codengine_awardbank_addresses', function($table)
            {
                $table->dropColumn('business_name');

            });
        }

        if (Schema::hasColumn('codengine_awardbank_addresses','attn_name')) {
       
            Schema::table('codengine_awardbank_addresses', function($table)
            {
                $table->dropColumn('attn_name');

            });
        }

        if (Schema::hasColumn('codengine_awardbank_addresses','floor')) {
       
            Schema::table('codengine_awardbank_addresses', function($table)
            {
                $table->dropColumn('floor');

            });
        }

    }
}