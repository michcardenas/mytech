<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;

class MeetingPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function view(User $user, Meeting $meeting): bool
    {
        return $meeting->user_id === $user->id;
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return $meeting->user_id === $user->id;
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return $meeting->user_id === $user->id;
    }
}
