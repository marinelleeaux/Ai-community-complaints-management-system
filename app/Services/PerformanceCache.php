<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PerformanceCache
{
    /**
     * Cache duration in seconds (1 hour for static/lookup, 30 min for metrics, 10 min for feeds)
     */
    public const LOOKUP_TTL = 3600;
    public const METRICS_TTL = 1800;
    public const FEEDS_TTL = 600;

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
        Cache::forget('admin_officials_list');
        Cache::forget('admin_complaints_index_default');
        Cache::forget('admin_residents_index_default');
        Cache::forget('admin_residents_pending_default');
        Cache::forget('admin_announcements_index_default');
        Cache::forget('admin_reports_default_data');
    }

    /**
     * Clear resident specific dashboard and layout caches
     */
    public static function clearResident(int|string|null $residentId = null): void
    {
        if ($residentId) {
            Cache::forget("resident_dashboard_{$residentId}");
            Cache::forget("resident_{$residentId}_complaints_default");
            Cache::forget("user_{$residentId}_unread_notifs_count");
            Cache::forget("user_{$residentId}_recent_notifs");
            Cache::forget("user_{$residentId}_active_complaints_count");
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
        Cache::forget('settings_puroks_with_counts');
        Cache::forget('settings_categories_with_counts');
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
