<?php

namespace App\Services\Finances;

use App\Services\IBaseService;
use App\Exceptions\CustomErrors\AppException;
use App\Helpers\ValidateFields;
use App\Repositories\Finances\Contracts\IWalletRepository;

class UpdateWalletService implements IBaseService
{
    private $walletRepository;

    public function __construct(IWalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * Atualiza uma carteira
     *
     * @param array $attributes
     *
     * @return bool
     * @throws AppException
     */
    public function execute(array $attributes): bool
    {
        $fieldsRequired = ['id'];

        ValidateFields::required($fieldsRequired, $attributes);

        $wallet = $this->walletRepository->findOne($attributes['id']);

        if (!$wallet) {
            throw new AppException('Wallet does not exist.', 401);
        }

        $wallet = $this->walletRepository->update($attributes['id'], $attributes);

        return $wallet;
    }
}
