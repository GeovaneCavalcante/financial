<?php

namespace App\Services\Finances;

use App\Services\IBaseService;
use App\Exceptions\CustomErrors\AppException;
use App\Helpers\ValidateFields;
use App\Repositories\Finances\Contracts\IWalletRepository;
use App\Repositories\Users\Contracts\IUserRepository;

class CreateWalletService implements IBaseService
{
    private $walletRepository;
    private $userRepository;

    public function __construct(IWalletRepository $walletRepository, IUserRepository $userRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->userRepository   = $userRepository;
    }

    /**
     * Cria uma nova carteira para um usuário
     *
     * @param array $attributes
     *
     * @return object
     * @throws AppException
     */
    public function execute(array $attributes): object
    {
        $fieldsRequired = ['user_id'];

        ValidateFields::required($fieldsRequired, $attributes);

        $userExist = $this->userRepository->findOne($attributes['user_id']);

        if (!$userExist) {
            throw new AppException('User does not exist.', 401);
        }

        $wallet['user_id'] = $attributes['user_id'];
        $wallet['balance'] = 500.00;

        $wallet = $this->walletRepository->save($wallet);

        return $wallet;
    }
}
