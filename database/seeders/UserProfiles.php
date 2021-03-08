<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class UserProfiles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_profiles')->insert(
            [
                'id'         => '516d2501-72ab-49a7-bd62-0a8949929014',
                'name'       => 'comum',
                'created_at' => Carbon::now('America/Sao_Paulo'),
                'updated_at' => Carbon::now('America/Sao_Paulo'),
            ]
        );
        DB::table('user_profiles')->insert(
            [
                'id'         => 'df13adc5-1d01-4113-8119-a6e3f7836fc0',
                'name'       => 'lojista',
                'created_at' => Carbon::now('America/Sao_Paulo'),
                'updated_at' => Carbon::now('America/Sao_Paulo'),
            ],
        );
    }
}
