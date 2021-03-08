<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class Permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert(
            [
                'id'         => 'a8cf78d7-6284-4e54-a7fa-f9fdfe922abe',
                'name'       => 'Make Transaction',
                'codename'   => 'make_transaction',
                'created_at' => Carbon::now('America/Sao_Paulo'),
                'updated_at' => Carbon::now('America/Sao_Paulo'),
            ],
        );
        DB::table('permissions')->insert(
            [
                'id'         => '584535df-9e3d-47f7-b58f-9974b27efc66',
                'name'       => 'Receive Transaction',
                'codename'   => 'receive_transaction',
                'created_at' => Carbon::now('America/Sao_Paulo'),
                'updated_at' => Carbon::now('America/Sao_Paulo'),
            ]
        );
    }
}
