<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BeauticianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //php artisan db:seed --class=BeauticianSeeder

        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => 'Beautician' . $i,
                'customer_id' => BEAUTICIAN_UNIQUE_ID . '/' . Carbon::now()->year . '/' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'email' => 'beautician' . $i . '@tech.com',
                'phone' => '123456789' . $i,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $user->assignRole(ROLE_BEAUTICIAN);
        }
    }
}
