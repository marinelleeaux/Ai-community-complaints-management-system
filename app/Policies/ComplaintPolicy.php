<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isActive();
    }

    public function view(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin() || $user->isOfficial()) {
            return true;
        }

        return $complaint->resident_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isApproved() && $user->isActive();
    }

    public function update(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin() || $user->isOfficial()) {
            return true;
        }

        // Residents can only edit their own complaint if still in 'submitted' status
        return $complaint->resident_id === $user->id && $complaint->status === 'submitted';
    }

    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->isAdmin();
    }

    public function addInternalNote(User $user, Complaint $complaint): bool
    {
        return $user->isAdmin() || $user->isOfficial();
    }

    public function changeStatus(User $user, Complaint $complaint): bool
    {
        return $user->isAdmin() || $user->isOfficial();
    }
}
