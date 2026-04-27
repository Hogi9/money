<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;

class WalletPolicy
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
        return $user->hasPermissionTo('view-wallets');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-wallets');
    }

    public function update(User $user, Wallet $wallet): bool
    {
        return $user->hasPermissionTo('edit-wallets')
            && $wallet->user_id === $user->id;
    }

    public function delete(User $user, Wallet $wallet): bool
    {
        return $user->hasPermissionTo('delete-wallets')
            && $wallet->user_id === $user->id;
    }
}
