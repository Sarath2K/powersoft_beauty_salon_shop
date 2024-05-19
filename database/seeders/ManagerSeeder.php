<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Manager',
            'customer_id' => MANAGER_UNIQUE_ID . '/' . Carbon::now()->year . '/0001',
            'email' => 'manager@tech.com',
            'phone' => '0123456789',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user->assignRole(ROLE_MANAGER);
    }
}
