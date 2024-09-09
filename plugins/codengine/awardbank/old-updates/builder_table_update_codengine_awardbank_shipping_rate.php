<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankShippingRate extends Migration
{
    public function up()
    {

        if(Schema::hasTable('codengine_awardbank_delivery') && (Schema::hasTable('codengine_awardbank_shipping_rate') == false)){

            Schema::rename('codengine_awardbank_delivery', 'codengine_awardbank_shipping_rate');

        }

        if (Schema::hasColumn('codengine_awardbank_shipping_rate','base_id') == false) {

            Schema::table('codengine_awardbank_shipping_rate', function($table)
            {
                $table->integer('base_id');
            });
        }
    }
    
    public function down()
    {
        if(Schema::hasTable('codengine_awardbank_shipping_rate')  && (Schema::hasTable('codengine_awardbank_delivery') == false)){

            Schema::rename('codengine_awardbank_shipping_rate', 'codengine_awardbank_delivery');

        }

        if (Schema::hasColumn('codengine_awardbank_delivery','base_id')) { 
               
            Schema::table('codengine_awardbank_delivery', function($table)
            {
                $table->dropColumn('base_id');
            });
        }
    }
}
