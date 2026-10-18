<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PerformanceCache
{
    /**
     * Cache duration in seconds (5 minutes for static/lookup, 60 seconds for live feeds)
     */
    public const LOOKUP_TTL = 300;
    public const METRICS_TTL = 60;
    public const FEEDS_TTL = 30;

    /**
     * Clear all admin dashboard and layout caches
     */
    public static function clearAdminDashboard(): void
    {
        Cache::forget('admin_dashboard_metrics');
        Cache::forget('admin_dashboard_recent_complaints');
        Cache::forget('admin_dashboard_pending_residents');
        Cache::forget('admin_nav_pending_complaints');
        Cache::forget('admin_nav_pending_residents');
    }

    /**
     * Clear resident specific dashboard and layout caches
     */
    public static function clearResident(int|string|null $residentId = null): void
    {
        if ($residentId) {
            Cache::forget("resident_dashboard_{$residentId}");
            Cache::forget("user_{$residentId}_unread_notifs_count");
            Cache::forget("user_{$residentId}_recent_notifs");
        }
    }

    /**
     * Clear global lookups (Puroks, Categories)
     */
    public static function clearLookups(): void
    {
        Cache::forget('active_puroks_cached');
        Cache::forget('active_categories_cached');
        Cache::forget('published_announcements_count');
    }

    /**
     * Nuclear cache wipe on critical events
     */
    public static function clearAll(): void
    {
        self::clearAdminDashboard();
        self::clearLookups();
    }
}
