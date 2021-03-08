<?php

namespace App\Repositories\Users;

use App\Repositories\BaseRepository;
use App\Models\UserProfile;
use App\Repositories\Users\Contracts\IUserProfileRepository;

class UserProfileRepository extends BaseRepository implements IUserProfileRepository
{
    protected $userProfile;

    public function __construct(UserProfile $userProfile)
    {
        parent::__construct($userProfile);
    }

}
