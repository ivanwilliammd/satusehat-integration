<?php

namespace Ivanwilliammd\SatusehatIntegration;

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
                __DIR__.'/../migrations/create_satusehat_token_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_token_table.php"),
            ], 'migrations');
        }

        // Publish Migrations for Log
        if (! class_exists('CreateSatusehatLogTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_satusehat_log_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_log_table.php"),
            ], 'migrations');
        }
    }

    public function register()
    {

    }
}
