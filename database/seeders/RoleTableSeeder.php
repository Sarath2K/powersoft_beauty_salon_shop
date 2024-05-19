<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->delete();

        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => ROLE_ADMIN,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => ROLE_MANAGER,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => ROLE_CUSTOMER,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
