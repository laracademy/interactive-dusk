<?php

namespace Laracademy\Commands;

use Illuminate\Support\ServiceProvider;

class DuskInteractiveServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        //
    }

    public function register()
    {
        $this->registerModelGenerator();
    }

    private function registerModelGenerator()
    {
        $this->app->singleton('command.laracademy.duskinteractive', function ($app) {
            return $app['Laracademy\Commands\Commands\DuskInteractiveCommand'];
        });
        $this->commands('command.laracademy.duskinteractive');
    }
}
