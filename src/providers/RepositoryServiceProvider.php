<?php

namespace Phpno1\Repository\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/repository.php' => config_path('repository.php')
        ],'repository');

        
        $this->mergeConfigFrom(__DIR__ . '/../config/repository.php', 'repository');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'repository');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Phpno1\Console\Commands\CreateEntity');
        //end-binding
    }
}
