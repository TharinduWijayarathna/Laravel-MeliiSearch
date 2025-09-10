<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MeiliSearch Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your MeiliSearch settings. MeiliSearch is a
    | powerful, fast, and easy to use search engine.
    |
    */

    'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
    'key' => env('MEILISEARCH_KEY', 'masterKey'),
    'timeout' => env('MEILISEARCH_TIMEOUT', 10),
];
