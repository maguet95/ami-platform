<?php

return [
    // Feature flag — set to false to disable the entire journal module
    'enabled' => env('JOURNAL_ENABLED', true),

    // Max entries per API request
    'max_entries_per_request' => 100,

    // Max summaries per API request
    'max_summaries_per_request' => 50,

    // Rate limit for API endpoints (requests per minute)
    'api_rate_limit' => 60,

    // Manual journal (Bitacora) settings
    'manual_enabled' => env('MANUAL_JOURNAL_ENABLED', true),
    'manual_max_images' => 5,
    'manual_max_image_size' => 2048, // KB

    // Stats & exports
    'stats_enabled' => env('JOURNAL_STATS_ENABLED', true),
    'exports_enabled' => env('JOURNAL_EXPORTS_ENABLED', true),
];
