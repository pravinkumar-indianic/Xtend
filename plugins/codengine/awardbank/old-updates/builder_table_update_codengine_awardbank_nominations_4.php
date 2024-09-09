<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankNominations4 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->integer('award_id')->nullable()->change();
            $table->integer('nominated_user_id')->nullable()->change();
            $table->integer('created_user_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_nominations', function($table)
        {
            $table->integer('award_id')->nullable(false)->change();
            $table->integer('nominated_user_id')->nullable(false)->change();
            $table->integer('created_user_id')->nullable(false)->change();
        });
    }
}
