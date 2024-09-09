<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPosts2 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_posts', function($table)
        {
            $table->integer('poster_id')->nullable();
            $table->integer('alias_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_posts', function($table)
        {
            $table->dropColumn('poster_id');
            $table->dropColumn('alias_id');
        });
    }
}
