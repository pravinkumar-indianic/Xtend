<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards14 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->string('nomination_secondary_header')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->dropColumn('nomination_secondary_header');
        });
    }
}
