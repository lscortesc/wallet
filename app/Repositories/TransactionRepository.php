<?php

namespace App\Repositories;

use App\Transaction;
use Illuminate\Support\Facades\App;

/**
 * Class TransactionRepository
 * @package App\Repositories
 */
class TransactionRepository
{
    /**
     * @var
     */
    private $model;

    /**
     * TransactionRepository constructor.
     */
    public function __construct()
    {
        $this->model = App::make(Transaction::class);
    }

    /**
     * @param int $walletId
     * @param $data
     * @return Transaction
     */
    public function create(int $walletId, $data)
    {
        $transaction = $this->model;
        $transaction->amount = $data['amount'];
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