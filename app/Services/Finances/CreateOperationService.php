<?php

namespace App\Services\Finances;

use App\Exceptions\CustomErrors\AppException;
use App\Services\IBaseService;
use App\Services\Auth\CheckPermissionTransactionService;
use App\Services\Auth\TransactionAuthorizer;
use App\Jobs\OperationNotification;

class CreateOperationService implements IBaseService
{
    private $checkPermissionTransactionService;
    private $showUserWalletService;
    private $updateWalletService;
    private $createTransactionService;
    private $transactionAuthorizer;


    public function __construct(
        CheckPermissionTransactionService $checkPermissionTransactionService,
        ShowUserWalletService $showUserWalletService,
        UpdateWalletService $updateWalletService,
        CreateTransactionService $createTransactionService,
        TransactionAuthorizer $transactionAuthorizer
    ) {
        $this->checkPermissionTransactionService = $checkPermissionTransactionService;
        $this->showUserWalletService             = $showUserWalletService;
        $this->updateWalletService               = $updateWalletService;
        $this->createTransactionService          = $createTransactionService;
        $this->transactionAuthorizer             = $transactionAuthorizer;
    }

    /**
     * Fazer uma operação de transação entre duas carteiras
     *
     * @param array $attributes
     *
     * @return object
     * @throws AppException
     */
    public function execute(array $attributes): object
    {
        $dtoPayer = array(
            "id"   => $attributes['payer'],
            "name" => 'make_transaction'
        );

        $checkPermissionMakeTransaction = $this->checkPermissionTransactionService->execute($dtoPayer);

        if (!$checkPermissionMakeTransaction) {
            throw new AppException('User is not allowed to make a transaction.', 401);
        }

        $dtoPayee = array(
            "id"   => $attributes['payee'],
            "name" => 'receive_transaction'
        );

        $checkPermissionReceiveTransaction = $this->checkPermissionTransactionService->execute($dtoPayee);

        if (!$checkPermissionReceiveTransaction) {
            throw new AppException('User is not allowed to receive a transaction.', 401);
        }

        $this->transactionAuthorizer->execute();

        $payerWallet = (object)$this->showUserWalletService->execute($dtoPayer);

        if (floatval($payerWallet->balance) < floatval($attributes['value'])) {
            throw new AppException('Insufficient balance to carry out transaction.', 401);
        }

        $payeeWallet = (object)$this->showUserWalletService->execute($dtoPayee);

        $payeeWallet->balance = floatval($payeeWallet->balance) + floatval($attributes['value']);

        $payerWallet->balance = floatval($payerWallet->balance) - floatval($attributes['value']);

        if (!$this->updateWalletService->execute((array)$payeeWallet)) {
            throw new AppException('Failed to update wallet.', 401);
        }

        if (!$this->updateWalletService->execute((array)$payerWallet)) {
            $payeeWallet->balance = floatval($payeeWallet->balance) - floatval($attributes['value']);
            $this->updateWalletService->execute((array)$payeeWallet);

            throw new AppException('Failed to update wallet.', 401);
        }

        $dtoTransaction = array(
            "sender_id"   => $attributes['payer'],
            "receiver_id" => $attributes['payee'],
            "value"       => $attributes['value'],
        );

        $transaction = $this->createTransactionService->execute($dtoTransaction);

        OperationNotification::dispatch($attributes['payee'])->onQueue('notifications');

        return $transaction;
    }
}
