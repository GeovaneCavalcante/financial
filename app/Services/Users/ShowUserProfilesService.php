<?php

namespace App\Services\Users;

use App\Repositories\Users\Contracts\IUserProfileRepository;
use App\Services\IBaseService;


class ShowUserProfilesService implements IBaseService
{

    protected $userProfileRepository;

    public function __construct(IUserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    /**
     * Retorna perfis de usuários
     *
     * @param array $attributes
     *
     * @return object
     */
    public function execute(array $attributes = []): object
    {
        $userProfiles = $this->userProfileRepository->all();
        return $userProfiles;
    }
}
