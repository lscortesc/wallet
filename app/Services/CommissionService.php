<?php

namespace App\Services;

use App\Transaction;
use App\Repositories\CommissionRepository;

/**
 * Class CommissionService
 * @package App\Services
 */
class CommissionService
{
    /**
     * @var CommissionRepository
     */
    private $repository;

    /**
     * CommissionService constructor.
     * @param CommissionRepository $repository
     */
    public function __construct(CommissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param float $amount
     * @return array
     */
    public function generateCommission(float $amount)
    {
        $percentage = 0;
        $fixedRate = 0;

        if ($amount < 1000) {
            $percentage = 0.03;
            $fixedRate = 8;
        }

        if ($amount >= 1000 && $amount < 5000) {
            $percentage = 0.025;
            $fixedRate = 6;
        }

        if ($amount >= 5000 && $amount < 10000) {
            $percentage = 0.02;
            $fixedRate = 4;
        }

        if ($amount >= 10000) {
            $percentage = 0.01;
            $fixedRate = 3;
        }

        $commission = $amount * $percentage + $fixedRate;
        $amountWithCommission = $amount - $commission;

        return [
            'percentage' => $percentage,
            'fixed_rate' => $fixedRate,
            'amount' => $amount,
            'amount_with_commission' => $amountWithCommission,
            'commission' => $commission
        ];
    }

    /**
     * @param Transaction $transaction
     * @param array $data
     * @return \App\Commission
     */
    public function saveCommission(Transaction $transaction, array $data)
    {
        return $this->repository->create($transaction->id, $data);
    }
}