<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin',
                'username' => 'admin',
                'email'    => 'admin@fulgenz.net',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'User',
                'username' => 'user',
                'email'    => 'user@fulgenz.net',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->syncRoles([$role]);
        }
    }
}
