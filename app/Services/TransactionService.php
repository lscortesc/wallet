<?php

namespace App\Services;

use App\Transaction;
use App\Wallet;
use App\Commission;
use App\Repositories\WalletRepository;
use Gateway\Contracts\PaymentInterface;
use App\Repositories\TransactionRepository;

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
     * Send Transfer Transaction
     */
    const TRANSACTION_SEND_TRANSFER = 'send_transfer';

    /**
     * Transfer Transaction Received
     */
    const TRANSACTION_TRANSFER_RECEIVED = 'transfer_received';

    /**
     * Transfer to Account
     */
    const TRANSACTION_TRANSFER_ACCOUNT = 'transfer_account';

    /**
     * Commission Type
     */
    const TRANSACTION_COMMISSION = 'commission';

    /**
     * @var CommissionService
     */
    private $commissionService;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * TransactionService constructor.
     * @param CommissionService $commissionService
     * @param TransactionRepository $repository
     * @param WalletRepository $walletRepository
     */
    public function __construct(
        CommissionService $commissionService,
        TransactionRepository $repository,
        WalletRepository $walletRepository
    ) {
        $this->commissionService = $commissionService;
        $this->repository = $repository;
        $this->walletRepository = $walletRepository;
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

        $transaction = $this->makeTransaction($wallet, $amount, $transfer);

        if ($transfer['authorized']) {
            $this->walletRepository->updateBalance(
                $wallet,
                $transaction['transaction']->amount_with_commission,
                'increments'
            );

            $this->transferToGeneralWallet($transaction['commission'], $transfer);
        }

        return $transaction['transaction'];
    }

    /**
     * @param PaymentInterface $gateway
     * @param Wallet $wallet
     * @param float $amount
     * @param array $accountData
     * @return Transaction
     */
    public function transferToAccount(
        PaymentInterface $gateway,
        Wallet $wallet,
        float $amount,
        array $accountData
    ) {
        $transfer = $gateway->transfer($amount, $accountData);
        $transfer['type'] = self::TRANSACTION_TRANSFER_ACCOUNT;

        $transaction = $this->makeTransaction($wallet, $amount, $transfer);
        $this->transferToGeneralWallet($transaction['commission'], $transfer);

        if ($transfer['authorized']) {
            $this->walletRepository->updateBalance($wallet, $amount, 'decrements');
        }

        return $transaction['transaction'];
    }

    /**
     * @param PaymentInterface $gateway
     * @param Wallet $sender
     * @param Wallet $receiver
     * @param float $amount
     * @param array $data
     * @return Transaction
     */
    public function transferToAnotherCustomer(
        PaymentInterface $gateway,
        Wallet $sender,
        Wallet $receiver,
        float $amount,
        array $data
    ) {
        $transfer = $gateway->transfer($amount, $data);
        $transfer['type'] = self::TRANSACTION_SEND_TRANSFER;

        // Send Transaction
        $sendTransaction = $this->makeTransaction($sender, $amount, $transfer);
        $this->transferToGeneralWallet($sendTransaction['commission'], $transfer);

        // Receiver transaction
        $transfer['type'] = self::TRANSACTION_TRANSFER_RECEIVED;
        $amountReceived = $sendTransaction['transaction']->amount_with_commission;
        $this->makeTransaction($receiver, $amountReceived, $transfer);

        if ($transfer['authorized']) {
            $this->walletRepository->updateBalance($sender, $amount, 'decrements');
            $this->walletRepository->updateBalance(
                $receiver,
                $sendTransaction['transaction']->amount_with_commission,
                'increments'
            );
        }

        return $sendTransaction['transaction'];
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param array $data
     * @return array
     */
    protected function makeTransaction(Wallet $wallet, float $amount, array $data): array
    {
        $commissions = $this->commissionService->generateCommission($amount, $data['type']);
        $data = array_merge($data, $commissions);

        if (! $data['authorized']) {
            $data['commission'] = 0;
            $data['percentage'] = 0;
            $data['fixed_rate'] = 0;
        }

        $transaction = $this->repository->create($wallet->id, $data);

        return [
            'transaction' => $transaction,
            'commission' => $this->commissionService->saveCommission($transaction, $data)
        ];
    }

    /**
     * @param Commission $commission
     * @param array $data
     * @return Wallet
     */
    protected function transferToGeneralWallet(Commission $commission, array $data)
    {
        $data['type'] = self::TRANSACTION_COMMISSION;
        $generalAccount = $this->walletRepository->getGeneralAccount();
        $this->makeTransaction($generalAccount, $commission->amount, $data);

        $generalAccount = $this->walletRepository->updateBalance(
            $generalAccount,
            $commission->amount,
            'increments'
        );

        return $generalAccount;
    }
}
