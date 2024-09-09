<?php namespace RainLab\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateUsers4 extends Migration
{

	public function up()
	{

        if (Schema::hasColumn('users','commencement_date') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->timestamp('commencement_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','termination_date') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->timestamp('termination_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','birth_date') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->timestamp('birth_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','birth_date') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->timestamp('birth_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_team_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_team_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_teams_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('current_all_teams_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_region_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_region_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_regions_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('current_all_regions_id')->nullable();

		    });
	    }


        if (Schema::hasColumn('users','current_program_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_program_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_programs_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('current_all_programs_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_org_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_org_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_orgs_id') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->text('current_all_orgs_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_home_addr_string') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->string('current_home_addr_string')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_shipping_addr_string') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->string('current_home_shipping_string')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','phone_number') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->string('phone_number')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_total_points') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_total_points')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_points_pending') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_points_pending')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_points_spent') == false) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->integer('current_points_spent')->nullable();

		    });
	    }
	}

	public function down()
	{
       if (Schema::hasColumn('users','commencement_date')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('commencement_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','termination_date')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('termination_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','birth_date')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('birth_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','birth_date')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('birth_date')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_team_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_team_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_teams_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_all_teams_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_region_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_region_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_regions_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_all_regions_id')->nullable();

		    });
	    }


        if (Schema::hasColumn('users','current_program_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_program_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_programs_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_all_programs_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_org_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_org_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_all_orgs_id')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_all_orgs_id')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_home_addr_string')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_home_addr_string')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_shipping_addr_string')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_home_shipping_string')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','phone_number')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('phone_number')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_total_points')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_total_points')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_points_pending')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_points_pending')->nullable();

		    });
	    }

        if (Schema::hasColumn('users','current_points_spent')) {

		    Schema::table('users', function($table)
		    {
		    	
		        $table->dropColumn('current_points_spent')->nullable();

		    });
	    }
	}
}