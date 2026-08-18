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

// Required by OAuth2Client::__construct()
// Dotenv will load real .env if present; these defaults satisfy __construct()
$_ENV['SATUSEHAT_ENV'] = $_ENV['SATUSEHAT_ENV'] ?? 'STG';
$_ENV['SATUSEHAT_BASE_URL_STG'] = $_ENV['SATUSEHAT_BASE_URL_STG'] ?? 'https://api-satusehat-stg.dto.kemkes.go.id';
$_ENV['CLIENTID_STG'] = $_ENV['CLIENTID_STG'] ?? 'test-client-id';
$_ENV['CLIENTSECRET_STG'] = $_ENV['CLIENTSECRET_STG'] ?? 'test-client-secret';
$_ENV['ORGID_STG'] = $_ENV['ORGID_STG'] ?? 'test-org-id';
