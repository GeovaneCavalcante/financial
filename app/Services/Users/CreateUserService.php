<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Hash;
use App\Repositories\Users\Contracts\IUserRepository;
use App\Services\IBaseService;

class CreateUserService implements IBaseService
{
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Cria um novo usuário
     *
     * @param array $attributes
     *
     * @return object
     */
    public function execute(array $attributes): object
    {
        $attributes['password'] = Hash::make($attributes['password']);

        $user = $this->userRepository->save($attributes);

        return $user;
    }
}
