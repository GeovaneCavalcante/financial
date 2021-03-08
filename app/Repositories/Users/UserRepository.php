<?php

namespace App\Repositories\Users;

use App\Repositories\BaseRepository;
use App\Models\User;
use App\Repositories\Users\Contracts\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Retorna todas permissões de um usuário
     *
     * @param string $permission
     * @param        $user_id
     *
     * @return object
     */
    public function findUserPermission(string $permission, $user_id): object
    {
        return $this->obj->with('profile.permissions')
            ->where('id', '=', $user_id)
            ->whereHas(
                'profile.permissions',
                function ($query) use ($permission) {
                    return $query->where('codename', '=', $permission);
                }
            )->get();
    }

    /**
     * Busca a cateira de um usuário
     *
     * @param $user_id
     *
     * @return object|null
     */
    public function findUserWallet($user_id): ?object
    {
        return $this->obj->with('wallet')->where('id', '=', $user_id)->first();
    }
}
