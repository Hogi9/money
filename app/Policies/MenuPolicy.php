<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function before(User $user): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-menus');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-menus');
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->hasPermissionTo('edit-menus');
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->hasPermissionTo('delete-menus');
    }
}
