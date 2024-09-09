<?php namespace Codengine\Awardbank\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateCodengineAwardbankPrograms5 extends Migration
{

    public function up()
    {

        if (Schema::hasColumn('codengine_awardbank_programs','new_account_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_account_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_account_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('new_account_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_account_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_account_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','unsubscribe_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('unsubscribe_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','unsubscribe_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('unsubscribe_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','unsubscribe_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('unsubscribe_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_placed_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('order_placed_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_placed_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('order_placed_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_placed_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('order_placed_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_shipped_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('order_shipped_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_shipped_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('order_shipped_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_shipped_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('order_shipped_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','product_backorder_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('product_backorder_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','product_backorder_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('product_backorder_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','product_backorder_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('product_backorder_body')->nullable();

            });
        }
        
       if (Schema::hasColumn('codengine_awardbank_programs','award_result_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('award_result_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_result_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('award_result_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_result_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('award_result_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_nomination_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('award_nomination_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_nomination_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('award_nomination_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_nomination_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('award_nomination_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_vote_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('award_vote_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_vote_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('award_vote_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_vote_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('award_vote_body')->nullable();

            });
        }

       if (Schema::hasColumn('codengine_awardbank_programs','award_winner_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('award_winner_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_winner_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('award_winner_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_winner_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('award_winner_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_post_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_post_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_post_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('new_post_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_post_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_post_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_comment_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_comment_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_comment_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('new_comment_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_comment_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_comment_body')->nullable();

            });
        }

       if (Schema::hasColumn('codengine_awardbank_programs','new_thankyou_send') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->boolean('new_thankyou_send')->default(1);

            });
        }

       if (Schema::hasColumn('codengine_awardbank_programs','new_thankyou_template') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('new_thankyou_template')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_thankyou_body') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('new_thankyou_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','facebook') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->text('facebook')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','twitter') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('twitter')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','instagram') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('instagram')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','pinterest') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('pinterest')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','linkedin') == false) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->string('linkedin')->nullable();

            });
        }
    }

    public function down()
    {
       if (Schema::hasColumn('codengine_awardbank_programs','new_account_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_account_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_account_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_account_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_account_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_account_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','unsubscribe_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('unsubscribe_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','unsubscribe_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('unsubscribe_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','unsubscribe_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('unsubscribe_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_placed_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('order_placed_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_placed_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('order_placed_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_placed_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('order_placed_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_shipped_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('order_shipped_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_shipped_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('order_shipped_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','order_shipped_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('order_shipped_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','product_backorder_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('product_backorder_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','product_backorder_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('product_backorder_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','product_backorder_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('product_backorder_body')->nullable();

            });
        }
        
       if (Schema::hasColumn('codengine_awardbank_programs','award_result_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_result_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_result_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_result_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_result_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_result_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_nomination_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_nomination_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_nomination_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_nomination_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_nomination_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_nomination_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_vote_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_vote_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_vote_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_vote_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_vote_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_vote_body')->nullable();

            });
        }

       if (Schema::hasColumn('codengine_awardbank_programs','award_winner_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_winner_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_winner_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_winner_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','award_winner_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('award_winner_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_post_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_post_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_post_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_post_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_post_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_post_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_comment_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_comment_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_comment_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_comment_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_comment_body') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_comment_body')->nullable();

            });
        }

       if (Schema::hasColumn('codengine_awardbank_programs','new_thankyou_send') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_thankyou_send')->default(1);

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_thankyou_body')) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_thankyou_body')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','new_thankyou_template') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('new_thankyou_template')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','facebook') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('facebook')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','twitter') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('twitter')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','instagram') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('instagram')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','pinterest') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('pinterest')->nullable();

            });
        }

        if (Schema::hasColumn('codengine_awardbank_programs','linkedin') ) {

            Schema::table('codengine_awardbank_programs', function($table)
            {
                
                $table->dropColumn('linkedin')->nullable();

            });
        } 
    }
}
