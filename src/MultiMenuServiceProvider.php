<?php

namespace Itstructure\MultiMenu;

use Illuminate\Support\ServiceProvider;
use Itstructure\MultiMenu\ViewComposers\MultiMenuComposer;

/**
 * Class MultiMenuServiceProvider
 *
 * @package Itstructure\MultiMenu
 *
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class MultiMenuServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViews();

        $this->publishConfig();
    }

    public function register()
    {
        $this->app->singleton('multiMenuWidget', function() {
            return new \Itstructure\MultiMenu\MultiMenuWidget();
        });

    }

    /**
     * @return void
     */
    private function loadViews(): void
    {
        $this->loadViewsFrom(base_path('resources/views/vendor/multiMenu'), 'multiMenuWidget');

        $this->publishes([
            $this->packagePath('resources/views') => base_path('resources/views/vendor/multiMenu'),
        ], 'views');
    }

    /**
     * @return void
     */
    private function publishConfig(): void
    {
        $configPath = $this->packagePath('config/multiMenu.php');

        $this->publishes([
            $configPath => config_path('multiMenu.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'multiMenu');
    }

    /**
     * @param $path
     *
     * @return string
     */
    private function packagePath($path): string
    {
        return __DIR__."/../$path";
    }
}
