<?php
/**
 * Example config for standalone queue worker.
 * Copy to config.php and fill in your credentials.
 *
 * For SQLite: ensure the directory is writable by the web server.
 * For MySQL: use 'mysql:host=...;dbname=...' in the PDO DSN.
 */

return [
    // SQLite path (relative to project root or absolute)
    'database' => __DIR__ . '/queue.db',

    // OAuth2 SATUSEHAT credentials
    'oauth2' => [
        // Base URL for OAuth2 token endpoint
        'base_url'  => env('SATUSEHAT_BASE_URL', 'https://api-satusehat.kemkes.go.id'),
        // FHIR base URL
        'fhir_url'  => env('SATUSEHAT_FHIR_URL', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1'),
        // OAuth2 client credentials
        'client_id'     => env('SATUSEHAT_CLIENT_ID', ''),
        'client_secret' => env('SATUSEHAT_CLIENT_SECRET', ''),
    ],

    // Optional: worker settings
    'worker' => [
        'limit' => 50,
    ],
];
