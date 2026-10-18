<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Cache;

class CachedEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier with local file caching.
     * Prevents expensive cross-ocean TLS + query round-trips on every HTTP click.
     */
    public function retrieveById($identifier)
    {
        if (empty($identifier)) {
            return null;
        }

        return Cache::remember("auth_user_{$identifier}", 600, function () use ($identifier) {
            $user = parent::retrieveById($identifier);
            if ($user) {
                $user->loadMissing('purok');
            }
            return $user;
        });
    }
}
