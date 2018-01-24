<?php

namespace Gateway\Payments\DummyBankPayment;

use Gateway\Contracts\PaymentInterface;

/**
 * Class DummyBankPayment
 * @package Gateway\Payments\DummyBankPayment
 */
class DummyBankPayment implements PaymentInterface
{
    /**
     * @param float $amount
     * @param array $data
     * @return array
     */
    public function transfer(float $amount, array $data)
    {
        $transactionNumber = uniqid('DBMP_');
        $authorized = (bool) random_int(0, 1);
        $message = $authorized ? 'Transaction successfully' : 'Transaction failed';
        $status = $authorized ? 'accepted' : 'declined';
        
        return [
            'transaction_number' => $transactionNumber,
            'authorized' => $authorized,
            'message' => $message,
            'status' => $status
        ];
    }
}
