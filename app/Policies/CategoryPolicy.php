<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
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
        return $user->hasPermissionTo('view-categories');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-categories');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('edit-categories')
            && $category->user_id === $user->id;
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('delete-categories')
            && $category->user_id === $user->id;
    }
}
