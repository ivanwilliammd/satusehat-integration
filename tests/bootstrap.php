<?php
/**
 * Standalone PHPUnit bootstrap — stubs Laravel helpers + sets required env
 * so FHIR classes (extending OAuth2Client) can be unit-tested
 * without Orchestra/Testbench or real .env.
 */

// Stub Laravel config() helper
if (! function_exists('config')) {
    function config($key, $default = null) {
        return $default;
    }
}

// Stub base_path() if not defined (OAuth2Client checks this)
if (! function_exists('base_path')) {
    function base_path(string $path = ''): string {
        return __DIR__ . '/..' . ($path ? '/' . ltrim($path, '/') : '');
    }
}

// Required by OAuth2Client::__construct().
// OAuth2Client uses getenv() (not $_ENV), so we MUST use putenv().
$testEnv = [
    'SATUSEHAT_ENV' => 'STG',
    'SATUSEHAT_BASE_URL_STG' => 'https://api-satusehat-stg.dto.kemkes.go.id',
    'CLIENTID_STG' => 'test-client-id',
    'CLIENTSECRET_STG' => 'test-client-secret',
    'ORGID_STG' => 'test-org-id',
];
foreach ($testEnv as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}
