<?php

namespace Modules;

use \Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Modules\User\src\Commands\SendMail;
use Modules\User\src\Http\MiddleWares\DemoMiddleware;
use Modules\User\src\Repositories\UserRepository;

class ModuleServiceProvider extends ServiceProvider
{
    private   $middlewares = [
        'demo' => DemoMiddleware::class
    ];

    private $commands = [
        SendMail::class
    ];

    public function boot()
    {
        $directories = $this->getDirectories();

        if (!empty($directories)) {
            foreach ($directories as $directory) {
                $this->registerModule($directory);
            }
        }
    }


    public function register()
    {
        $directories = $this->getDirectories();
        if (!empty($directories)) {
            foreach ($directories as $directory) {
                $this->registerConfig($directory);
            }
        }
        //Middleware


        $this->registerMiddlewares();

        //Command
        $this->commands($this->commands);

        $this->app->singleton(
            UserRepository::class
        );

    }

    private function getDirectories()
    {
        $directories = array_map('basename', File::directories(__DIR__));
        return $directories;
    }

    private function registerConfig($module){
        $configPath = __DIR__ . '/' . $module . '/configs';

        if (File::exists($configPath)) {

            $configFiles = array_map('basename', File::allFiles($configPath));

            foreach ($configFiles as $config) {

                $alias = basename($config, '.php');

                $this->mergeConfigFrom($configPath . '/' . $config, $alias);

            }

        }
    }

    private function registerModule($module)
    {
        $modulePath = __DIR__ . '/' . $module;

        // Declaration route
        if (File::exists($modulePath . '/routes/routes.php')) {
             $this->loadRoutesFrom($modulePath . '/routes/routes.php');
        }

        //Declaration migration
        if (File::exists($modulePath . '/migrations')) {
            $this->loadMigrationsFrom($modulePath . '/migrations');
        }

        //Declaration languages
        if (File::exists($modulePath . '/resources/lang')) {
            $this->loadTranslationsFrom($modulePath . '/resources/lang',strtolower($module));
        }

        //Declaration views
        if (File::exists($modulePath . '/resources/views')) {
            $this->loadViewsFrom($modulePath . '/resources/views',strtolower($module));
        }

        //Declaration helpers
        if (File::exists($modulePath . 'helpers')) {
            $helper_dir = File::allFiles($modulePath . '/helpers');
            foreach ($helper_dir as $value) {
                $file = $value->getPathname();
                require $file;
            }
        }
    }

    private function registerMiddlewares(){
        if (!empty($this->middlewares)) {
            foreach ($this->middlewares as $key => $middleware) {
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            }
        }
    }

}
