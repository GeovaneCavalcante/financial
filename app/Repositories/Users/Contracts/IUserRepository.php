<?php

namespace App\Repositories\Users\Contracts;

use App\Repositories\IBaseRepository;

interface IUserRepository extends IBaseRepository
{
    public function findUserPermission(string $permission, $user_id): object;

    public function findUserWallet($user_id): ?object;
}
