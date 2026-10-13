<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Supabase Auth, REST API, and Storage.
    |
    */

    'url' => env('SUPABASE_URL', 'https://your-project.supabase.co'),
    'anon_key' => env('SUPABASE_ANON_KEY', env('SUPABASE_PUBLISHABLE_KEY', '')),
    'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY', env('SUPABASE_SECRET_KEY', '')),

    /*
    |--------------------------------------------------------------------------
    | Supabase Storage Buckets
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'attachments_bucket' => env('SUPABASE_STORAGE_ATTACHMENTS_BUCKET', 'complaint-attachments'),
        'avatars_bucket' => env('SUPABASE_STORAGE_AVATARS_BUCKET', 'avatars'),
        'announcements_bucket' => env('SUPABASE_STORAGE_ANNOUNCEMENTS_BUCKET', 'announcements'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Settings
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'auto_confirm_email' => env('SUPABASE_AUTH_AUTO_CONFIRM', true),
        'jwt_secret' => env('SUPABASE_JWT_SECRET', ''),
    ],
];
