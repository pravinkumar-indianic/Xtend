<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderUpdateTableIndexing extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_category_allocation', function($table)
        {
            $table->index('category_id');
        });

        Schema::table('codengine_awardbank_results', function($table)
        {
            $table->index('resulttype_id');
            $table->index('month_index');
            $table->index('team_id');
        });
    }

    public function down()
    {
        Schema::table('codengine_awardbank_category_allocation', function($table)
        {
            $table->dropIndex('category_id');
        });

        Schema::table('codengine_awardbank_results', function($table)
        {
            $table->dropIndex('resulttype_id');
            $table->dropIndex('month_index');
            $table->dropIndex('team_id');
        });
    }
}
