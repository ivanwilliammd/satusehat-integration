<?php

namespace Satusehat\Integration;

use Illuminate\Support\ServiceProvider;

class SatusehatIntegrationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/satusehatintegration.php' => config_path('satusehatintegration.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/satusehatintegration.php', 'satusehatintegration');

        if ($this->app->runningInConsole()) {
            $publishesMigrationsMethod = method_exists($this, 'publishesMigrations')
                ? 'publishesMigrations'
                : 'publishes';

            $this->{$publishesMigrationsMethod}([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'satusehat-migrations');
        }

        // Publish ICD-10 csv data
        $this->publishes([
            __DIR__ . '/../database/seeders/csv/icd10.csv.stub' => database_path('/seeders/csv/icd10.csv'),
        ], 'icd10');

        // Publish Seeder for ICD10
        if (!class_exists('Icd10Seeder')) {
            $this->publishes([
                __DIR__ . '/../database/seeders/Icd10Seeder.php.stub' => database_path('/seeders/Icd10Seeder.php'),
            ], 'icd10');
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
