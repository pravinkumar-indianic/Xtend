<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms7 extends Migration
{
    public function up()
    {
        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->integer('birthday_prize_value')->default(0);
        });

        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->integer('anniversary_prize_value')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('birthday_prize_value')->nullable();
        });

        Schema::table('codengine_awardbank_programs', function($table)
        {
            $table->dropColumn('anniversary_prize_value')->nullable();
        });
    }
}
