<?php

namespace AdrianBav\Traffic;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use AdrianBav\Traffic\Contracts\RobotDetection;

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

        $this->createTrafficDbConnection();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MigrateCommand::class,
            ]);
        }
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

        $this->app->bind(RobotDetection::class, CrawlerRobotDetection::class);

        $this->app->singleton('traffic', function ($app) {
            return new Traffic(
                $app['config']['traffic'],
                $app->make(RobotDetection::class)
            );
        });
    }

    /**
     * Clone the configured package connection to a new connection 'traffic'
     * in the main applications database connections.
     *
     * @return void
     */
    private function createTrafficDbConnection()
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
