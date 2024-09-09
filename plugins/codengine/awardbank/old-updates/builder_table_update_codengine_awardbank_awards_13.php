<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards13 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->string('nomination_header')->nullable();
            $table->string('nomination_submit_button_text')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_awards', function($table)
        {
            $table->dropColumn('nomination_header');
            $table->dropColumn('nomination_submit_button_text');
        });
    }
}
