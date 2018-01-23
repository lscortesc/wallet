<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Wallet;
use Gateway\Contracts\PaymentInterface;

/**
 * Class TransactionService
 * @package App\Services
 */
class TransactionService
{
    /**
     * Transaction fund
     */
    const TRANSACTION_FUND = 'fund';

    /**
     * @var CommissionService
     */
    private $commissionService;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * TransactionService constructor.
     * @param CommissionService $commissionService
     * @param TransactionRepository $repository
     */
    public function __construct(
        CommissionService $commissionService,
        TransactionRepository $repository) {
        $this->commissionService = $commissionService;
        $this->repository = $repository;
    }

    /**
     * @param PaymentInterface $gateway
     * @param float $amount
     * @param array $cardData
     * @param Wallet $wallet
     * @return mixed
     */
    public function fundWallet(
        PaymentInterface $gateway,
        Wallet $wallet,
        float $amount,
        array $cardData
    ) {
        $transfer = $gateway->transfer($amount, $cardData);
        $transfer['type'] = self::TRANSACTION_FUND;

        $commissions = $this->commissionService->generateCommission($amount);
        $transfer = array_merge($transfer, $commissions);

        $transaction = $this->makeTransaction($wallet->id, $transfer);

        return $transfer;
    }

    /**
     * @param int $walletId
     * @param array $data
     * @return array
     */
    public function makeTransaction(int $walletId, array $data): array
    {
        $transaction = $this->repository->create($walletId, $data);

        if (! $transaction->authorized) {
            $data['commission'] = 0;
            $data['amount'] = 0;
            $data['percentage'] = 0;
            $data['fixed_rate'] = 0;
        }

        return [
            'transaction' => $transaction,
            'commission' => $this->commissionService->saveCommission($transaction, $data)
        ];
    }
}
