<?php

namespace App\Repositories\Finances;

use App\Repositories\BaseRepository;
use App\Models\Wallet;
use App\Repositories\Finances\Contracts\IWalletRepository;

class WalletRepository extends BaseRepository implements IWalletRepository
{
    protected $wallet;

    public function __construct(Wallet $wallet)
    {
        parent::__construct($wallet);
    }
}
