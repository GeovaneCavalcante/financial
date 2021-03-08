<?php

namespace App\Services\Finances;

use App\Exceptions\CustomErrors\AppException;
use App\Services\IBaseService;
use App\Repositories\Users\Contracts\IUserRepository;

class ShowUserWalletService implements IBaseService
{

    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retorna uma carteira com base no id do usuário
     *
     * @param array $attributes
     *
     * @return mixed
     * @throws AppException
     */
    public function execute(array $attributes): array
    {
        $user = $this->userRepository->findUserWallet($attributes['id']);

        if (!isset($user->wallet)) {
            throw new AppException('Wallet does not exist.', 401);
        }

        return $user->wallet->toArray();
    }
}
