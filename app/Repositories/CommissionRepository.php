<?php

namespace App\Repositories;

use App\Commission;

/**
 * Class CommissionRepository
 * @package App\Repositories
 */
class CommissionRepository
{
    /**
     * @param int $transactionId
     * @param array $data
     * @return Commission
     */
    public function create(int $transactionId, array $data): Commission
    {
        $commission = new Commission;
        $commission->amount = $data['commission'];
        $commission->percentage = $data['percentage'];
        $commission->fixed_rate = $data['fixed_rate'];
        $commission->transaction_id = $transactionId;

        $commission->save();

        return $commission;
    }
}
