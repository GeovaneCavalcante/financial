<?php

namespace App\Services\Finances;

use App\Services\IBaseService;
use App\Helpers\ValidateFields;
use App\Repositories\Finances\Contracts\ITransactionRepository;

class CreateTransactionService implements IBaseService
{
    private $transactionRepository;

    public function __construct(ITransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Cria uma nova transação
     *
     * @param array $attributes
     *
     * @return object
     * @throws \App\Exceptions\CustomErrors\AppException
     */
    public function execute(array $attributes): object
    {
        $fieldsRequired = ['value', 'receiver_id', 'sender_id'];

        ValidateFields::required($fieldsRequired, $attributes);

        $transaction = $this->transactionRepository->save($attributes);

        return $transaction;
    }
}
