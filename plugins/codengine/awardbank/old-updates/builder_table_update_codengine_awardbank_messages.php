<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankMessages extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_messages', function($table)
        {
            $table->boolean('read')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_messages', function($table)
        {
            $table->dropColumn('read');
        });
    }
}
