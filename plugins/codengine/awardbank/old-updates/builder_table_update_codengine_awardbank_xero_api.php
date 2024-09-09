<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankXeroApi extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_xero_api', function($table)
        {
            $table->integer('id');
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_xero_api', function($table)
        {
            $table->dropPrimary(['id']);
            $table->dropColumn('id');
        });
    }
}
