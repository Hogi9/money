<?php

namespace Database\Seeders;

use App\Models\Menu;

class MenuSeeder extends \Illuminate\Database\Seeder
{
    public function run(): void
    {
        // Top-level menus
        $dashboard = Menu::firstOrCreate(['name' => 'Dashboard'], [
            'icon'            => 'tabler:home',
            'route_name'      => 'dashboard',
            'parent_id'       => null,
            'group'           => null,
            'sort_order'      => 1,
            'is_active'       => true,
            'permission_name' => 'view-dashboard',
        ]);

        // Master Finance dropdown parent
        $masterFinanceParent = Menu::firstOrCreate(['name' => 'Master Keuangan'], [
            'icon'            => 'tabler:category',
            'route_name'      => null,
            'parent_id'       => null,
            'group'           => 'Finance',
            'sort_order'      => 2,
            'is_active'       => true,
            'permission_name' => null,
        ]);

        Menu::firstOrCreate(['name' => 'Kategori', 'parent_id' => $masterFinanceParent->id], [
            'icon'            => 'tabler:tag',
            'route_name'      => 'categories.index',
            'group'           => null,
            'sort_order'      => 1,
            'is_active'       => true,
            'permission_name' => 'view-categories',
        ]);

        Menu::firstOrCreate(['name' => 'Nama Transaksi', 'parent_id' => $masterFinanceParent->id], [
            'icon'            => 'tabler:list-details',
            'route_name'      => 'transaction-names.index',
            'group'           => null,
            'sort_order'      => 2,
            'is_active'       => true,
            'permission_name' => 'view-transaction-names',
        ]);

        // Finance dropdown parent
        $financeParent = Menu::firstOrCreate(['name' => 'Keuangan'], [
            'icon'            => 'tabler:cash',
            'route_name'      => null,
            'parent_id'       => null,
            'group'           => 'Finance',
            'sort_order'      => 3,
            'is_active'       => true,
            'permission_name' => null,
        ]);

        Menu::firstOrCreate(['name' => 'Dompet', 'parent_id' => $financeParent->id], [
            'icon'            => 'tabler:wallet',
            'route_name'      => 'wallets.index',
            'group'           => null,
            'sort_order'      => 1,
            'is_active'       => true,
            'permission_name' => 'view-wallets',
        ]);

        Menu::firstOrCreate(['name' => 'Transaksi', 'parent_id' => $financeParent->id], [
            'icon'            => 'tabler:transfer',
            'route_name'      => 'transactions.index',
            'group'           => null,
            'sort_order'      => 2,
            'is_active'       => true,
            'permission_name' => 'view-transactions',
        ]);

        $users = Menu::firstOrCreate(['name' => 'Users'], [
            'icon'            => 'tabler:users',
            'route_name'      => 'users.index',
            'parent_id'       => null,
            'group'           => 'Access Management',
            'sort_order'      => 4,
            'is_active'       => true,
            'permission_name' => 'view-users',
        ]);

        $teams = Menu::firstOrCreate(['name' => 'Teams'], [
            'icon'            => 'tabler:users-group',
            'route_name'      => 'teams.index',
            'parent_id'       => null,
            'group'           => null,
            'sort_order'      => 5,
            'is_active'       => true,
            'permission_name' => 'view-teams',
        ]);

        // Dropdown parent — no route, no permission (visibility governed by children)
        $rolesParent = Menu::firstOrCreate(['name' => 'Roles & Permission'], [
            'icon'            => 'tabler:shield-lock',
            'route_name'      => null,
            'parent_id'       => null,
            'group'           => null,
            'sort_order'      => 5,
            'is_active'       => true,
            'permission_name' => null,
        ]);

        // Children of Roles & Permission
        Menu::firstOrCreate(['name' => 'Roles', 'parent_id' => $rolesParent->id], [
            'icon'            => 'tabler:user-shield',
            'route_name'      => 'roles.index',
            'group'           => null,
            'sort_order'      => 1,
            'is_active'       => true,
            'permission_name' => 'view-roles',
        ]);

        Menu::firstOrCreate(['name' => 'Permissions', 'parent_id' => $rolesParent->id], [
            'icon'            => 'tabler:key',
            'route_name'      => 'permissions.index',
            'group'           => null,
            'sort_order'      => 2,
            'is_active'       => true,
            'permission_name' => 'view-permissions',
        ]);

        // Menus management (child of Roles & Permission dropdown)
        Menu::firstOrCreate(['name' => 'Menus', 'parent_id' => $rolesParent->id], [
            'icon'            => 'tabler:layout-sidebar',
            'route_name'      => 'menus.index',
            'group'           => null,
            'sort_order'      => 3,
            'is_active'       => true,
            'permission_name' => 'view-menus',
        ]);

        // Feedback — user: submit, admin: manage
        Menu::firstOrCreate(['name' => 'Kritik & Saran'], [
            'icon'            => 'tabler:message-report',
            'route_name'      => 'feedbacks.create',
            'parent_id'       => null,
            'group'           => 'Account',
            'sort_order'      => 6,
            'is_active'       => true,
            'permission_name' => 'create-feedbacks',
        ]);

        Menu::firstOrCreate(['name' => 'Kelola Feedback'], [
            'icon'            => 'tabler:message-check',
            'route_name'      => 'feedbacks.index',
            'parent_id'       => null,
            'group'           => 'Account',
            'sort_order'      => 7,
            'is_active'       => true,
            'permission_name' => 'view-feedbacks',
        ]);

        // Account group — profile (no permission = always visible)
        Menu::firstOrCreate(['name' => 'Profil'], [
            'icon'            => 'tabler:user',
            'route_name'      => 'profile.show',
            'parent_id'       => null,
            'group'           => 'Account',
            'sort_order'      => 8,
            'is_active'       => true,
            'permission_name' => null,
        ]);

        Menu::firstOrCreate(['name' => 'Logout'], [
            'icon'            => 'tabler:logout-2',
            'route_name'      => 'logout',
            'parent_id'       => null,
            'group'           => 'Account',
            'sort_order'      => 9,
            'is_active'       => true,
            'permission_name' => null,
        ]);
    }
}
