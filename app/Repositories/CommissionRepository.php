<?php

namespace App\Repositories;

use App\Commission;
use Illuminate\Support\Facades\App;

/**
 * Class CommissionRepository
 * @package App\Repositories
 */
class CommissionRepository
{
    /**
     * @var
     */
    private $model;

    /**
     * CommissionRepository constructor.
     */
    public function __construct()
    {
        $this->model = App::make(Commission::class);
    }

    /**
     * @param int $transactionId
     * @param array $data
     * @return Commission
     */
    public function create(int $transactionId, array $data): Commission
    {
        $commission = $this->model;
        $commission->amount = $data['commission'];
        $commission->percentage = $data['percentage'];
        $commission->fixed_rate = $data['fixed_rate'];
        $commission->transaction_id = $transactionId;

        $commission->save();

        return $commission;
    }
}
