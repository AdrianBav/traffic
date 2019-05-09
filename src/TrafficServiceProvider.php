<?php

namespace AdrianBav\Traffic;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class TrafficServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any traffic services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/traffic.php' => config_path('traffic.php'),
        ]);

        $this->setConnection();

        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations'
        );
    }

    /**
     * Register any traffic services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/traffic.php', 'traffic'
        );

        $this->app->singleton('traffic', function ($app) {
            return new Traffic(
                $app['config']->get('traffic.site_slug'),
                $app['config']->get('traffic.single_visit')
            );
        });
    }

    /**
     * Clone the configured package connection to a new connection 'traffic'
     * in the main applications database connections.
     *
     * @return void
     */
    protected function setConnection()
    {
        $connection = Config::get('traffic.database_default');

        if ($connection == 'app-default') {
            $connection = Config::get('database.default');
            $trafficConnection = Config::get('database.connections.'.$connection);
        } else {
            $trafficConnection = Config::get('traffic.database_connections.'.$connection);
        }

        Config::set('database.connections.traffic', $trafficConnection);
    }
}
