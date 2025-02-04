<?php

namespace Database\Seeders\Table;

use App\Models\Role\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Admin'
            ],
            [
                'name' => 'user',
                'description' => 'User'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
