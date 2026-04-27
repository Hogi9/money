<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-permissions');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-permissions');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('edit-permissions');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('delete-permissions');
    }
}
