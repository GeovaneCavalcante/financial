<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\Permissions;
use Database\Seeders\UserProfiles;
use Database\Seeders\UserProfilePermissions;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                Permissions::class,
                UserProfiles::class,
                UserProfilePermissions::class,
            ]
        );
    }
}
