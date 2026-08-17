<?php
/**
 * Standalone PHPUnit bootstrap — stubs Laravel helpers
 * so FHIR classes (extending OAuth2Client) can be unit-tested
 * without Orchestra/Testbench.
 */

if (! function_exists('config')) {
    function config($key, $default = null) {
        return $default;
    }
}
