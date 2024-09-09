<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateCodengineAwardbankXeroApi extends Migration
{
    public function up()
    {
        Schema::create('codengine_awardbank_xero_api', function($table)
        {
            $table->engine = 'InnoDB';
            $table->text('access_token');
            $table->text('refresh_token');
            $table->string('token_type');
            $table->string('expires_in');
            $table->string('expires_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('codengine_awardbank_xero_api');
    }
}
