<?php

namespace App\Observers;

use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuObserver
{
    /**
     * Auto-create permission from permission_name and assign it to the admin role.
     */
    public function saved(Menu $menu): void
    {
        if (! $menu->permission_name) {
            return;
        }

        $permission = Permission::firstOrCreate([
            'name'       => $menu->permission_name,
            'guard_name' => 'web',
        ]);

        $admin = Role::findByName('admin', 'web');

        if ($admin && ! $admin->hasPermissionTo($permission)) {
            $admin->givePermissionTo($permission);
        }
    }
}
