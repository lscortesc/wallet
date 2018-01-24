<?php

namespace App\Http\Controllers;

use App\Customer;
use Oauth\Traits\ResponseTrait;
use App\Services\TransactionService;
use App\Repositories\WalletRepository;
use Oauth\Contracts\FormatterInterface;
use App\Http\Requests\FundWalletRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Requests\TransferBalanceRequest;
use App\Http\Requests\TransferAccountBalanceRequest;
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

        return $this->response(
            $wallet->with('currency')->find($wallet->id)
        );
    }

    /**
     * @param TransferBalanceRequest $request
     * @param Customer $customerReceiver
     * @return mixed
     */
    public function transferToAnotherCustomer(
        TransferBalanceRequest $request,
        Customer $customerReceiver
    ) {
        $transaction = $this->transactionService
            ->transferToAnotherCustomer(
                $this->gatewayPayment,
                $request->user()->wallet,
                $this->repository->getByCustomer($customerReceiver, ['customer', 'currency']),
                $request->get('amount'),
                $request->all()
            );

        return $this->response($transaction);
    }

    /**
     * @param TransferAccountBalanceRequest $request
     * @return FormatterInterface
     */
    public function transferToAccount(TransferAccountBalanceRequest $request)
    {
        $transaction = $this->transactionService
            ->transferToAccount(
                $this->gatewayPayment,
                $request->user()->wallet,
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
        return $this->response(
            $this->repository->getByCustomer(
                $request->user(),
                [
                    'currency',
                    'customer'
                ]
            )
        );
    }

    /**
     * @param Request $request
     * @return FormatterInterface
     */
    public function transactions(Request $request)
    {
        return $this->response(
            $this->repository->getByCustomer(
                $request->user(),
                [
                    'currency',
                    'transactions.commission'
                ]
            )
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function balanceAccountGeneral(Request $request)
    {
        if ($request->user()->wallet->id !== WalletRepository::GENERAL_ACCOUNT_ID) {
            return $this->response([
                "errors" => "You don't have permissions to consult it"
            ], 400);
        }

        return $this->response(
            $this->repository->getGeneralAccount()
        );
    }
}
