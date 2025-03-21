<?php

namespace MahmoudElsherbeny\LaravelGrapes;

use Illuminate\Support\ServiceProvider;
use MahmoudElsherbeny\LaravelGrapes\Interfaces\PageRepositoryInterface;
use MahmoudElsherbeny\LaravelGrapes\Repositories\PageRepository;
use MahmoudElsherbeny\LaravelGrapes\Interfaces\BlockRepositoryInterface;
use MahmoudElsherbeny\LaravelGrapes\Repositories\BlockRepository;

class LaravelGrapesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(BlockRepositoryInterface::class, BlockRepository::class);

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'lg');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lg');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                
                __DIR__.'/../config/config.php' => config_path('lg.php'),
                __DIR__.'/../resources/assets/js/PageBuilder.js' => base_path('public/js/laravel-grapes.js'),
                __DIR__.'/../resources/assets/css/laravel-grapes.css' => base_path('public/css/laravel-grapes.css'),
                __DIR__.'/../database/migrations/2022_11_27_020138_create_pages_table.php' => database_path('/migrations/'.date('Y_m_d_His', time()).'_create_pages_table.php'),
                __DIR__.'/../database/migrations/2022_12_06_015222_create_page_builder_custome_blocks_table.php' => database_path('/migrations/'.date('Y_m_d_His', time()).'_create_page_builder_custome_blocks_table.php'),

            ], '*');
        }

        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $webRoutesPath = base_path('routes/web.php');
        $pageBuilderRoute = "require __DIR__.'/page-builder.php';";

        if (! file_exists($webRoutesPath)) {
            return;
        }

        copy(__DIR__.'/../routes/page-builder.php', base_path('routes/page-builder.php'));

        
        $webRoutesContent  = file_get_contents($webRoutesPath);
        
        if (str_contains($webRoutesContent, $pageBuilderRoute)) {
            return;
        }

        $webRoutesContent .= PHP_EOL . $pageBuilderRoute . PHP_EOL;
        file_put_contents($webRoutesPath, $webRoutesContent);
    }
}
