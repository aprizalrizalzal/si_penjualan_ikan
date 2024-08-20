<?php

namespace Database\Seeders\Table;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password')

            ],
            [
                'name' => 'Pembeli',
                'email' => 'pembeli@email.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password')
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
