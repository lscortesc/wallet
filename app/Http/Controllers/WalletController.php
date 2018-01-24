<?php

namespace App\Http\Controllers;

use Oauth\Traits\ResponseTrait;
use App\Services\TransactionService;
use App\Http\Requests\FundWalletRequest;
use Gateway\Payments\DummyBankPayment\DummyBankPayment;

/**
 * Class WalletController
 * @package App\Http\Controllers
 */
class WalletController extends Controller
{
    use ResponseTrait;
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * WalletController constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param FundWalletRequest $request
     * @return mixed
     */
    public function fund(FundWalletRequest $request)
    {
        $wallet = $request->user()->wallet;
        $amount = $request->get('amount');

        $transaction = $this->transactionService->fundWallet(
            new DummyBankPayment(),
            $wallet,
            $amount,
            $request->all()
        );

        if (! $transaction['transaction']->authorized) {
            return $this->response([
                'error' => $transaction['transaction']
            ], 422);
        }

        $wallet->balance += ($amount - $transaction['commission']->amount);
        $wallet->save();

        $this->transactionService->transferToGeneralWallet($transaction['commission']);

        return $this->response($wallet);
    }
}
