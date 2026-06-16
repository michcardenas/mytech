<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

class ProposalPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function update(User $user, Proposal $proposal): bool
    {
        return $proposal->lead->user_id === $user->id;
    }

    public function delete(User $user, Proposal $proposal): bool
    {
        return $proposal->lead->user_id === $user->id;
    }
}
