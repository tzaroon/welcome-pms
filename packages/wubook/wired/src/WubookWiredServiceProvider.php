<?php
namespace Wubook\Wired;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Contracts\Container\Container;

class WubookWiredServiceProvider extends ServiceProvider {

    public function boot() 
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/wubook.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('wubook.php')]);
        }
        $this->mergeConfigFrom($source, 'wubook');
    }

    public function register()
    {
        $this->app->singleton('wubook', function (Container $app) {
            $config = $app['config'];
            return new WuBookManager($config);
        });

        $this->app->alias('wubook', WuBookManager::class);
    }
}