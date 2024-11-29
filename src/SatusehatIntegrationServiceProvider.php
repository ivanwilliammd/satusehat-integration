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

        // Publish Migrations for ICD 10
        if (! class_exists('CreateSatusehatIcd10Table')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_satusehat_icd10_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_icd10_table.php"),
            ], 'icd10');
        }

        // Publish ICD 10 csv data
        $this->publishes([
            __DIR__.'/../database/seeders/csv/icd10.csv.stub' => database_path('/seeders/csv/icd10.csv'),
        ], 'icd10');

        // Publish Seeder for ICD 10
        if (! class_exists('Icd10Seeder')) {
            $this->publishes([
                __DIR__.'/../database/seeders/Icd10Seeder.php.stub' => database_path('/seeders/Icd10Seeder.php'),
            ], 'icd10');
        }

        // Publish Migrations for ICD 9 CM
        if (! class_exists('CreateSatusehatIcd9cmTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_satusehat_icd9cm_table.php.stub' => database_path("/migrations/{$timestamp}_create_satusehat_icd9cm_table.php"),
            ], 'icd9cm');
        }

        // Publish ICD 9 CM csv data
        $this->publishes([
            __DIR__.'/../database/seeders/csv/icd9cm.csv.stub' => database_path('/seeders/csv/icd9cm.csv'),
        ], 'icd9cm');

        // Publish Seeder for ICD 9 CM
        if (! class_exists('Icd9cmSeeder')) {
            $this->publishes([
                __DIR__.'/../database/seeders/Icd9cmSeeder.php.stub' => database_path('/seeders/Icd9cmSeeder.php'),
            ], 'icd9cm');
        }

        // Publish LOINC & LOINC answer csv data
        $this->publishes([
            __DIR__.'/../database/seeders/csv/loinc.csv.stub' => database_path('/seeders/csv/loinc.csv'),
        ], 'loinc');

        $this->publishes([
            __DIR__.'/../database/seeders/csv/loinc_answer.csv.stub' => database_path('/seeders/csv/loinc_answer.csv'),
        ], 'loinc');

        // Publish Seeder for LOINC & LOINC Answer
        if (! class_exists('LoincSeeder')) {
            $this->publishes([
                __DIR__.'/../database/seeders/LoincSeeder.php.stub' => database_path('/seeders/LoincSeeder.php'),
            ], 'loinc');
        }

        if (! class_exists('LoincAnswerSeeder')) {
            $this->publishes([
                __DIR__.'/../database/seeders/LoincAnswerSeeder.php.stub' => database_path('/seeders/LoincAnswerSeeder.php'),
            ], 'loinc');
        }

        // Publish Migrations for Kode Wilayah Indonesia
        if (! class_exists('CreateKodeWilayahIndonesiaTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_kode_wilayah_indonesia_table.php.stub' => database_path("/migrations/{$timestamp}_create_kode_wilayah_indonesia_table.php"),
            ], 'kodewilayahindonesia');
        }

        // Publish Kode Wilayah Indonesia csv data
        $this->publishes([
            __DIR__.'/../database/seeders/csv/kode_wilayah_indonesia.csv.stub' => database_path('/seeders/csv/kode_wilayah_indonesia.csv'),
        ], 'kodewilayahindonesia');

        // Publish Seeder for Kode Wilayah Indonesia
        if (! class_exists('KodeWilayahIndonesiaSeeder')) {
            $this->publishes([
                __DIR__.'/../database/seeders/KodeWilayahIndonesiaSeeder.php.stub' => database_path('/seeders/KodeWilayahIndonesiaSeeder.php'),
            ], 'kodewilayahindonesia');
        }
    }

    public function register()
    {
        //
    }
}
