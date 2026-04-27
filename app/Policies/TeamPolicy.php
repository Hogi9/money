<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
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
        return $user->hasPermissionTo('view-teams');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('view-teams')
            && $team->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-teams');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('edit-teams')
            && $team->user_id === $user->id;
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('delete-teams')
            && $team->user_id === $user->id;
    }

    public function addMember(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('edit-teams')
            && $team->user_id === $user->id;
    }

    public function removeMember(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('edit-teams')
            && $team->user_id === $user->id;
    }
}
