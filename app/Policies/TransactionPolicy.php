<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
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
        return $user->hasPermissionTo('view-transactions');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-transactions');
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->hasPermissionTo('edit-transactions')
            && $transaction->user_id === $user->id;
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->hasPermissionTo('delete-transactions')
            && $transaction->user_id === $user->id;
    }
}
