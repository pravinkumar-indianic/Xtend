<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankXeroApi2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_xero_api', function($table)
        {
            $table->increments('id')->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_xero_api', function($table)
        {
            $table->integer('id')->change();
        });
    }
}
