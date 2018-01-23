<?php

namespace App\Http\Controllers;

use Oauth\Traits\ResponseTrait;
use App\Services\TransactionService;
use App\Http\Requests\FundWalletRequest;
use Gateway\Payments\DummyBankPayment\DummyBankPayment;

class WalletController extends Controller
{
    use ResponseTrait;
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function fund(FundWalletRequest $request)
    {
        $wallet = $request->user()->wallet;

        $transaction = $this->transactionService->fundWallet(
            new DummyBankPayment(),
            $wallet,
            $request->get('amount'),
            $request->all()
        );

        if (! $transaction['authorized']) {
            unset($transaction['percentage']);
            unset($transaction['fixed_rate']);
            unset($transaction['type']);
            unset($transaction['amount_with_commission']);
            unset($transaction['commission']);

            return $this->response([
                'error' => $transaction
            ], 422);
        }

        $wallet->balance += $transaction['amount_with_commission'];
        $wallet->save();

        return $this->response($wallet);
    }
}
