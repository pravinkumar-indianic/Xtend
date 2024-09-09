<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Transaction as Transaction;
use Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {

        $transaction = Transaction::create([
            'user_id'				=> '2',
            'paymentgateway_id'		=> '1',
            'value'					=> '50',
            'success'				=> 1
        ]);

        $transaction2 = Transaction::create([
            'user_id'				=> '1',
            'paymentgateway_id'		=> '2',
            'value'					=> '700',
            'success'				=> 1
        ]);
    }
}