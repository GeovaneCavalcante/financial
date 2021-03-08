<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class UserProfilePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All permissions for common user
        DB::table('user_profile_permissions')->insert(
            [
                'id'              => 'b1c59b06-5670-4796-a091-5589e9724777',
                'user_profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014',
                'permission_id'   => 'a8cf78d7-6284-4e54-a7fa-f9fdfe922abe',
                'created_at'      => Carbon::now('America/Sao_Paulo'),
                'updated_at'      => Carbon::now('America/Sao_Paulo'),
            ],
        );
        DB::table('user_profile_permissions')->insert(
            [
                'id'              => '584535df-9e3d-47f7-b58f-9974b27efc66',
                'user_profile_id' => '516d2501-72ab-49a7-bd62-0a8949929014',
                'permission_id'   => '584535df-9e3d-47f7-b58f-9974b27efc66',
                'created_at'      => Carbon::now('America/Sao_Paulo'),
                'updated_at'      => Carbon::now('America/Sao_Paulo'),
            ]
        );

        // Only merchant user reception permission
        DB::table('user_profile_permissions')->insert(
            [
                'id'              => '748b945c-1762-4d05-a146-d9bf53729fe0',
                'user_profile_id' => 'df13adc5-1d01-4113-8119-a6e3f7836fc0',
                'permission_id'   => '584535df-9e3d-47f7-b58f-9974b27efc66',
                'created_at'      => Carbon::now('America/Sao_Paulo'),
                'updated_at'      => Carbon::now('America/Sao_Paulo'),
            ]
        );
    }
}
