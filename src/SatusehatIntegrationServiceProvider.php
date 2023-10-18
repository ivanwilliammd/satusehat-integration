<?php

namespace Satusehat\Integration;

use Illuminate\Support\ServiceProvider;

class SatusehatIntegrationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish Config
        $this->publishes([
            __DIR__.'/../config/satusehatintegration.php' => config_path('satusehatintegration.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/satusehatintegration.php', 'satusehatintegration');

        // Publish Migrations for Token
        if (! class_exists('CreateSatusehatTokenTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_satusehat_token_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_token_table.php"),
            ], 'migrations');
        }

        // Publish Migrations for Log
        if (! class_exists('CreateSatusehatLogTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_satusehat_log_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_log_table.php"),
            ], 'migrations');
        }

        // Publish Migrations for ICD10
        if (! class_exists('CreateIcd10Table')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_satusehat_icd10_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_icd10_table.php"),
            ], 'migrations');
        }

        // Publish ICD-10 csv data
        $this->publishes([
            __DIR__.'/../database/migrations/create_satusehat_log_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_log_table.php"),
        ], 'icd10');

    }

    public function register()
    {
        //
    }
}
