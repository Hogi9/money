<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view-dashboard',
            'view-users',    'create-users',    'edit-users',    'delete-users',
            'view-roles',    'create-roles',    'edit-roles',    'delete-roles',
            'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions',
            'view-teams',    'create-teams',    'edit-teams',    'delete-teams',
            'view-menus',    'create-menus',    'edit-menus',    'delete-menus',
            // Finance permissions
            'view-categories',        'create-categories',        'edit-categories',        'delete-categories',
            'view-transaction-names', 'create-transaction-names', 'edit-transaction-names', 'delete-transaction-names',
            'view-wallets',           'create-wallets',           'edit-wallets',           'delete-wallets',
            'view-transactions',      'create-transactions',      'edit-transactions',      'delete-transactions',
            // Feedback permissions
            'view-feedbacks',         'create-feedbacks',         'edit-feedbacks',          'delete-feedbacks',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
