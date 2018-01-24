<?php

namespace App\Repositories;

use App\Wallet;
use App\Customer;

/**
 * Class WalletRepository
 * @package App\Repositories
 */
class WalletRepository
{
    /**
     * ID GENERAL ACCOUNT
     */
    const GENERAL_ACCOUNT_ID = 100000;

    /**
     * @return Wallet
     */
    public function getGeneralAccount()
    {
        return Wallet::find(self::GENERAL_ACCOUNT_ID);
    }

    /**
     * @param Customer $customer
     * @param array $related
     * @return Wallet
     */
    public function getByCustomer(Customer $customer, array $related): Wallet
    {
        $wallet = Wallet::where('customer_id', $customer->id);

        if (count($related) > 0) {
            $wallet->with($related);
        }

        return $wallet->first();
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param string $type
     * @return Wallet
     */
    public function updateBalance(Wallet $wallet, float $amount, string $type)
    {
        if ($type !== 'increments') {
            $amount *= -1;
        }

        $wallet->balance += $amount;
        $wallet->save();

        return $wallet;
    }
}