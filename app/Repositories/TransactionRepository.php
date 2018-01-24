<?php

namespace App\Repositories;

use App\Transaction;

/**
 * Class TransactionRepository
 * @package App\Repositories
 */
class TransactionRepository
{
    /**
     * @param int $walletId
     * @param $data
     * @return Transaction
     */
    public function create(int $walletId, $data)
    {
        $transaction = new Transaction;
        $transaction->amount = $data['amount'];
        $transaction->amount_with_commission = $data['amount_with_commission'];
        $transaction->authorized = $data['authorized'];
        $transaction->message = $data['message'];
        $transaction->transaction_number = $data['transaction_number'];
        $transaction->type = $data['type'];
        $transaction->status = $data['status'];
        $transaction->wallet_id = $walletId;
        $transaction->save();

        return $transaction;
    }
}
