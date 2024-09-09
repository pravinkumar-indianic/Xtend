<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards extends Migration
{
    public function up()
    {


        if (Schema::hasColumn('codengine_awardbank_awards','created_user_id')) {
       
            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('created_user_id');

            });
        }


        if (Schema::hasColumn('codengine_awardbank_awards','nominations_visibility_type')) {
       
            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('nominations_visibility_type');

            });
        }



        if (Schema::hasColumn('codengine_awardbank_awards','votes_visibility_type')) {
       
            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('votes_visibility_type');

            });
        }
    }
    
    public function down()
    {


        if (Schema::hasColumn('codengine_awardbank_awards','created_user_id') == false) {
       
            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->integer('created_user_id');

            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','nominations_visibility_type') == false) {
       
            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->string('nominations_visibility_type');

            });
        }    

        if (Schema::hasColumn('codengine_awardbank_awards','votes_visibility_type') == false) {
       
            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->string('votes_visibility_type');

            });
        }    
    }
}
