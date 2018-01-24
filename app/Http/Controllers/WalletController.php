<?php

namespace App\Http\Controllers;

use App\Wallet;
use App\Customer;
use Oauth\Traits\ResponseTrait;
use App\Services\TransactionService;
use App\Repositories\WalletRepository;
use Oauth\Contracts\FormatterInterface;
use App\Http\Requests\FundWalletRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Requests\TransferToAnotherCustomerRequest;
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
     * @var DummyBankPayment
     */
    private $gatewayPayment;

    /**
     * @var WalletRepository
     */
    private $repository;

    /**
     * WalletController constructor.
     * @param TransactionService $transactionService
     * @param WalletRepository $repository
     */
    public function __construct(
        TransactionService $transactionService,
        WalletRepository $repository
    ) {
        $this->transactionService = $transactionService;
        $this->gatewayPayment = new DummyBankPayment();
        $this->repository = $repository;
    }

    /**
     * @param FundWalletRequest $request
     * @return FormatterInterface
     */
    public function fund(FundWalletRequest $request)
    {
        $wallet = $request->user()->wallet;
        $amount = $request->get('amount');

        $transaction = $this->transactionService->fundWallet(
            $this->gatewayPayment,
            $wallet,
            $amount,
            $request->all()
        );

        if (! $transaction->authorized) {
            return $this->response([
                'error' => $transaction
            ], 422);
        }

        return $this->response($wallet->with('currency')->find($wallet->id));
    }

    /**
     * @param TransferToAnotherCustomerRequest $request
     * @param Customer $customerReceiver
     * @return mixed
     */
    public function transferToAnotherCustomer(
        TransferToAnotherCustomerRequest $request,
        Customer $customerReceiver
    ) {
        $wallet = $request->user()->wallet;

        $transaction = $this->transactionService
            ->transferToAnotherCustomer(
                $this->gatewayPayment,
                $wallet,
                $this->repository->getByCustomer($customerReceiver, ['customer', 'currency']),
                $request->get('amount'),
                $request->all()
            );

        return $this->response($transaction);
    }

    /**
     * @param Request $request
     * @return FormatterInterface
     */
    public function balance(Request $request)
    {
        $wallet = $request->user()->wallet;

        return $this->response(
            Wallet::with('currency')->find($wallet->id)
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function transactions(Request $request)
    {
        return $this->response(
            $request->user()
                ->wallet()
                ->with('currency', 'transactions.commission')
                ->first()
        );
    }
}
