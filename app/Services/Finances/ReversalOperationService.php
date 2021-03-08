<?php

namespace App\Services\Finances;

use App\Exceptions\CustomErrors\AppException;
use App\Repositories\Finances\Contracts\ITransactionRepository;
use App\Services\IBaseService;

class ReversalOperationService implements IBaseService
{

    private $showUserWalletService;
    private $updateWalletService;
    private $transactionRepository;

    public function __construct(
        ITransactionRepository $transactionRepository,
        ShowUserWalletService $showUserWalletService,
        UpdateWalletService $updateWalletService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->showUserWalletService     = $showUserWalletService;
        $this->updateWalletService   = $updateWalletService;
    }

    /**
     * Reverte uma transação com base no seu hash
     *
     * @param array $attributes
     *
     * @return object|null
     * @throws AppException
     */
    public function execute(array $attributes): ?object
    {
        $transaction = $this->transactionRepository->findOne($attributes['id']);

        $dtoPayer = array(
            "id" => $transaction->sender_id,
        );

        $payerWallet = (object)$this->showUserWalletService->execute($dtoPayer);

        $dtoPayee = array(
            "id" => $transaction->receiver_id,
        );

        $payeeWallet = (object)$this->showUserWalletService->execute($dtoPayee);

        $payeeWallet->balance = floatval($payeeWallet->balance) - floatval($transaction->value);

        $payerWallet->balance = floatval($payerWallet->balance) + floatval($transaction->value);

        if (!$this->updateWalletService->execute((array)$payeeWallet)) {
            throw new AppException('Failed to update wallet.', 401);
        }

        if (!$this->updateWalletService->execute((array)$payerWallet)) {
            $payeeWallet->balance = floatval($payeeWallet->balance) + floatval($transaction->value);
            $this->updateWalletService->execute((array)$payeeWallet);

            throw new AppException('Failed to update wallet.', 401);
        }

        $this->transactionRepository->delete($attributes['id']);

        return $transaction;
    }
}
