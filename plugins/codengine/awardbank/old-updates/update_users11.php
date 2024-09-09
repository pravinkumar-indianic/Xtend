<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers11 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','register_form_questions_answers') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('register_form_questions_answers')->nullable();

		    });
	    }
	}

	public function down()
	{

       if (Schema::hasColumn('users','register_form_questions_answers')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('register_form_questions_answers');

		    });
	    }
	}
}