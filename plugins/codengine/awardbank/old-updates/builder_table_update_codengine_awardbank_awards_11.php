<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards11 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->boolean('awardallprogramnominate')->default(0);
            $table->boolean('awardallprogramvote')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->dropColumn('awardallprogramnominate');
            $table->dropColumn('awardallprogramvote');
        });
    }
}
