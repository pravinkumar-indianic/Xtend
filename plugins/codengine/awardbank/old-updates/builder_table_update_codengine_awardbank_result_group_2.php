<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankResultGroup2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_result_group', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_result_group', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('deleted_at');
        });
    }
}
