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
        $this->commands('Phpno1\Repository\Generator\Commands\CreateEntity');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateController');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateCriteria');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateFilter');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateModel');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateRepository');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateRequest');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateResponse');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateSeeder');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateService');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateProvider');
        $this->commands('Phpno1\Repository\Generator\Commands\CreateBinding');
        //end-binding
    }
}
