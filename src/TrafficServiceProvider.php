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
        //
    }

    /**
     * Register any traffic services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('traffic', function($app) {
            return new Traffic;
        });
    }
}
