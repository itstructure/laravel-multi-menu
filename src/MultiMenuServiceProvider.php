<?php

namespace Itstructure\MultiMenu;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Itstructure\MultiMenu\Commands\PublishCommand;

/**
 * Class MultiMenuServiceProvider
 *
 * @package Itstructure\MultiMenu
 *
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class MultiMenuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerCommands();
    }

    public function boot()
    {
        $this->loadViews();

        $this->publishViews();

        $this->publishConfig();

        require_once __DIR__ . '/functions.php';

        Blade::directive('multiMenu', function ($options) {
            return "<?php echo multi_menu($options); ?>";
        });
    }

    /**
     * @return void
     */
    private function loadViews(): void
    {
        $this->loadViewsFrom($this->packagePath('resources/views'), 'multimenu');
    }

    /**
     * @return void
     */
    private function publishViews(): void
    {
        $this->publishes([
            $this->packagePath('resources/views') => resource_path('views/vendor/multimenu'),
        ], 'views');
    }

    /**
     * @return void
     */
    private function publishConfig(): void
    {
        $configPath = $this->packagePath('config/multimenu.php');

        $this->publishes([
            $configPath => config_path('multimenu.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'multimenu');
    }

    /**
     * @return void
     */
    private function registerCommands(): void
    {
        $this->commands(PublishCommand::class);
    }

    /**
     * @param $path
     * @return string
     */
    private function packagePath($path): string
    {
        return __DIR__ . "/../" . $path;
    }
}
