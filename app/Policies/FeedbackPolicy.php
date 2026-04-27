<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
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
        return $user->hasPermissionTo('view-feedbacks');
    }

    public function view(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('view-feedbacks');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-feedbacks');
    }

    public function update(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('edit-feedbacks');
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('delete-feedbacks');
    }
}
