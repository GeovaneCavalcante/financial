<?php

namespace App\Repositories\Finances;

use App\Repositories\BaseRepository;
use App\Models\Transaction;
use App\Repositories\Finances\Contracts\ITransactionRepository;

class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
    }
}

