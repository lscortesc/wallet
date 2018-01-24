<?php

namespace Gateway\Contracts;

/**
 * Interface PaymentInterface
 * @package Gateway\Contracts
 */
interface PaymentInterface
{
    /**
     * @param float $amount
     * @param array $data
     * @return mixed
     */
    public function transfer(float $amount, array $data);
}
