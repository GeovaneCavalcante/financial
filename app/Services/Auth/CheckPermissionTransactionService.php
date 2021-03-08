<?php

namespace App\Services\Auth;

use App\Services\IBaseService;
use App\Helpers\ValidateFields;
use App\Repositories\Users\Contracts\IUserRepository;

class CheckPermissionTransactionService implements IBaseService
{

    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retorna usuário e suas permissões
     *
     * @param array $attributes
     *
     * @return object|null
     * @throws \App\Exceptions\CustomErrors\AppException
     */
    public function execute(array $attributes): ?object
    {
        $fieldsRequired = ['id'];

        ValidateFields::required($fieldsRequired, $attributes);

        $permissions = $this->userRepository->findUserPermission($attributes['name'], $attributes['id']);

        return $permissions->first();
    }
}
