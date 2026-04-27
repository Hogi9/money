<?php

namespace App\Policies;

use App\Models\TransactionName;
use App\Models\User;

class TransactionNamePolicy
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
        return $user->hasPermissionTo('view-transaction-names');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-transaction-names');
    }

    public function update(User $user, TransactionName $transactionName): bool
    {
        return $user->hasPermissionTo('edit-transaction-names')
            && $transactionName->user_id === $user->id;
    }

    public function delete(User $user, TransactionName $transactionName): bool
    {
        return $user->hasPermissionTo('delete-transaction-names')
            && $transactionName->user_id === $user->id;
    }
}
