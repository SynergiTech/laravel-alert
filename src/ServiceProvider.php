<?php

namespace SynergiTech\Alert;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $basePath = __DIR__ . '/../';
        $configPath = $basePath . 'config/alert.php';

        // publish config
        $this->publishes([
            $configPath => config_path('alert.php'),
        ], 'config');

        // include the config file from the package if it isn't published
        $this->mergeConfigFrom($configPath, 'alert');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton('synergitech.alert', function () {
            return $this->app->make('SynergiTech\Alert\Alert');
        });
    }
}
