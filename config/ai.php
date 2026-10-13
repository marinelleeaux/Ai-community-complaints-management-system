<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | Supported providers: 'gemini', 'openai', 'anthropic', 'mock'
    |
    */

    'provider' => env('AI_PROVIDER', 'gemini'),

    'api_key' => env('AI_API_KEY', ''),

    'model' => env('AI_MODEL', 'gemini-1.5-flash'),

    'timeout' => env('AI_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Barangay Context for AI Assistant
    |--------------------------------------------------------------------------
    */
    'barangay' => [
        'name' => env('BARANGAY_NAME', 'Barangay North Poblacion'),
        'municipality' => env('MUNICIPALITY_NAME', 'Maramag'),
        'province' => env('PROVINCE_NAME', 'Bukidnon'),
        'country' => 'Philippines',
        'office_hours' => 'Monday to Friday, 8:00 AM - 5:00 PM',
        'hotline' => '(088) 123-4567 / 0917-123-4567',
        'emergency_numbers' => [
            'Police Station' => '0917-000-1111',
            'Fire Station' => '0917-000-2222',
            'MDRRMO / Ambulance' => '0917-000-3333',
            'Barangay Tanod Outpost' => '0917-000-4444',
        ],
    ],
];
