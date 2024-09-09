<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankAwards7 extends Migration
{
    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_awards','nomination_display_string') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->string('nomination_display_string')->default('Nomination');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','nomination_file_upload') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->boolean('nomination_file_upload')->default(0);
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','nomination_image_upload') == false) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->boolean('nomination_image_upload')->default(0);
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('codengine_awardbank_awards','nomination_display_string')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('nomination_display_string');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','nomination_image_upload')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('nomination_image_upload');
            });
        }

        if (Schema::hasColumn('codengine_awardbank_awards','nomination_file_upload')) {

            Schema::table('codengine_awardbank_awards', function($table)
            {
                $table->dropColumn('nomination_file_upload');
            });
        }

    }
}
