<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    /** El admin puede todo. */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('comercial');
    }

    public function view(User $user, Lead $lead): bool
    {
        return $lead->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('comercial');
    }

    public function update(User $user, Lead $lead): bool
    {
        return $lead->user_id === $user->id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $lead->user_id === $user->id;
    }

    /** Solo el admin convierte un lead en proyecto (lo hace `before`). */
    public function convert(User $user, Lead $lead): bool
    {
        return false;
    }
}
