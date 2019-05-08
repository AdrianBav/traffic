<?php

namespace AdrianBav\Traffic;

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
    }

    /**
     * Register any traffic services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('traffic', function ($app) {
            return new Traffic;
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/traffic.php', 'traffic'
        );
    }
}
