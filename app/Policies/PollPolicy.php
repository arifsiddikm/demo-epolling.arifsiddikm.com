<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    public function view(User $user, Poll $poll): bool
    {
        return $user->id === $poll->user_id || $user->isAdmin();
    }

    public function update(User $user, Poll $poll): bool
    {
        return $user->id === $poll->user_id || $user->isAdmin();
    }

    public function delete(User $user, Poll $poll): bool
    {
        return $user->id === $poll->user_id || $user->isAdmin();
    }
}
