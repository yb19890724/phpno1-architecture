<?php

namespace Phpno1\Repository\providers;

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
        //
         $this->mergeConfigFrom(__DIR__.'/../config/repository.php', 'repository');
         $this->loadTranslationsFrom(__DIR__ . '/../lang', 'repository');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }
}
