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
        //加载配置文件和语言包
         $this->mergeConfigFrom(__DIR__.'/../Config/repository.php', 'repository');
         $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'repository');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // 绑定接口和实现类关系
    }
}
