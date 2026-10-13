<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Announcement $announcement): bool
    {
        if ($announcement->publication_status === 'published') {
            return true;
        }

        return $user && ($user->isAdmin() || $user->isOfficial());
    }

    public function manage(User $user): bool
    {
        return $user->isAdmin() || $user->isOfficial();
    }
}
