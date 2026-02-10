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
];
