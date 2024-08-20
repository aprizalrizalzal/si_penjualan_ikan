<?php

namespace Database\Seeders\Table;

use App\Models\Role\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $users = User::where('email', 'pembeli@email.com')->get();
            foreach ($users as $user) {
                $user->roles()->syncWithoutDetaching([$userRole->id => ['created_at' => now(), 'updated_at' => now()]]);
            }
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admins = User::where('email', 'admin@email.com')->get();
            foreach ($admins as $user) {
                $user->roles()->syncWithoutDetaching([$adminRole->id => ['created_at' => now(), 'updated_at' => now()]]);
            }
        }
    }
}
