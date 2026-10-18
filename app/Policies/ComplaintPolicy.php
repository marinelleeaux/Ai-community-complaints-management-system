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
        // Strictly only the resident who owns the complaint can edit it (while submitted or under review)
        return $user->isResident() && $complaint->resident_id === $user->id && in_array($complaint->status, ['submitted', 'under_review']);
    }

    public function delete(User $user, Complaint $complaint): bool
    {
        // Strictly only the resident who owns the complaint can delete it (while submitted or under review)
        return $user->isResident() && $complaint->resident_id === $user->id && in_array($complaint->status, ['submitted', 'under_review']);
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
