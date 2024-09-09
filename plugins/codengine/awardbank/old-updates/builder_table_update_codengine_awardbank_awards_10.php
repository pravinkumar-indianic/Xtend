<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards10 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->boolean('awardallprogramview')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->dropColumn('awardallprogramview');
        });
    }
}
