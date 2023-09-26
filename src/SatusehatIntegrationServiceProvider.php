<?php

namespace Ivanwilliammd\SatusehatIntegration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class ActivitylogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/satusehatintegration.php' => config_path('satusehatintegration.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/satusehatintegration.php', 'satusehatintegration');

        if (! class_exists('CreateActivityLogTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_activity_log_table.php.stub' => database_path("/migrations/{$timestamp}_create_activity_log_table.php"),
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->app->bind('command.activitylog:clean', CleanActivitylogCommand::class);

        $this->commands([
            'command.activitylog:clean',
        ]);

        $this->app->bind(ActivityLogger::class);

        $this->app->singleton(ActivityLogStatus::class);
    }

    public static function determineActivityModel(): string
    {
        $activityModel = config('activitylog.activity_model') ?? ActivityModel::class;

        if (! is_a($activityModel, Activity::class, true)
            || ! is_a($activityModel, Model::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($activityModel);
        }

        return $activityModel;
    }

    public static function getActivityModelInstance(): ActivityContract
    {
        $activityModelClassName = self::determineActivityModel();

        return new $activityModelClassName();
    }
}
