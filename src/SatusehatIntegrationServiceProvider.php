<?php

namespace Satusehat\Integration;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Satusehat\Integration\Console\Commands\EnqueueSatusehat;
use Satusehat\Integration\Console\Commands\ProcessSatusehatQueue;
use Satusehat\Integration\Console\Commands\QueueStatusSatusehat;

class SatusehatIntegrationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerCommands();
    }

    public function register(): void
    {
        //
    }

    public function schedule(Schedule $schedule): void
    {
        // Default: run queue processor every minute
        // User can customize in app/Console/Kernel.php or remove via $schedule->command(...)->withoutOverlapping()
        $schedule->command('satusehat:process-queue --once --limit=50', [], config('app.env') === 'production' ? ['stopOnError' => false] : [])
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/satusehat-queue.log'));
    }

    private function registerPublishing(): void
    {
        // Publish Config
        $this->publishes([
            __DIR__.'/../config/satusehatintegration.php' => config_path('satusehatintegration.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/satusehatintegration.php', 'satusehatintegration');

        // Publish Migrations for Token
        if (! class_exists('CreateSatusehatTokenTable')) {
            $this->publishMigration(
                'CreateSatusehatTokenTable',
                __DIR__.'/../database/migrations/create_satusehat_token_table.php.stub',
                'migrations'
            );
        }

        // Publish Migrations for Log
        if (! class_exists('CreateSatusehatLogTable')) {
            $this->publishMigration(
                'CreateSatusehatLogTable',
                __DIR__.'/../database/migrations/create_satusehat_log_table.php.stub',
                'migrations'
            );
        }

        // Publish Migrations for Queue
        if (! class_exists('CreateSatusehatQueueTable')) {
            $this->publishMigration(
                'CreateSatusehatQueueTable',
                __DIR__.'/../database/migrations/create_satusehat_queue_table.php.stub',
                'queue'
            );
        }

        // Publish Migrations for ICD 10
        if (! class_exists('CreateSatusehatIcd10Table')) {
            $this->publishMigration(
                'CreateSatusehatIcd10Table',
                __DIR__.'/../database/migrations/create_satusehat_icd10_table.php.stub',
                'icd10'
            );
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

        // Publish Migrations for Kode Wilayah Indonesia
        if (! class_exists('CreateKodeWilayahIndonesiaTable')) {
            $this->publishMigration(
                'CreateKodeWilayahIndonesiaTable',
                __DIR__.'/../database/migrations/create_kode_wilayah_indonesia_table.php.stub',
                'kodewilayahindonesia'
            );
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

    private function publishMigration(string $className, string $stubPath, string $tag): void
    {
        $timestamp = date('Y_m_d_His', time());
        $this->publishes([
            $stubPath => database_path("/migrations/{$timestamp}_".basename($stubPath, '.stub').'.php'),
        ], $tag);
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                EnqueueSatusehat::class,
                ProcessSatusehatQueue::class,
                QueueStatusSatusehat::class,
            ]);
        }
    }
}
