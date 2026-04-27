<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Admin — full access to everything
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // User — dashboard + full CRUD on finance modules
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->syncPermissions([
            'view-dashboard',
            'view-wallets',    'create-wallets',    'edit-wallets',    'delete-wallets',
            'view-transactions', 'create-transactions', 'edit-transactions', 'delete-transactions',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-transaction-names', 'create-transaction-names', 'edit-transaction-names', 'delete-transaction-names',
            'create-feedbacks',
        ]);
    }
}
