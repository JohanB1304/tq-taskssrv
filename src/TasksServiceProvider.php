<?php

namespace TqTaskssrv\TqTaskssrv;

use Illuminate\Support\ServiceProvider;

class TasksServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerRoutes();
        $this->loadMigrations();
        $this->loadModels();
        $this->loadConfig();
    }

    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->publishes(
            [
                __DIR__ . '/../routes' => base_path('routes'),
            ],
            'tasks-package'
        );
        

    }

    private function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes(
            [
                __DIR__ . '/../database/migrations' => base_path('database/migrations'),
            ],
            'tasks-package'
        );
    }

    private function loadControllers()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Http/Controllers/TasksController.php');

        $this->publishes(
            [
                __DIR__ . '/Http/Controllers/TasksController.php' => base_path('app/Http/Controllers/TasksController.php'),
            ],
            'annotations-package'
        );
    }

    private function loadModels()
    {
        $this->publishes(
            [
                __DIR__ . '/Models/TsTasks.php' => base_path('app/Models/TsTasks.php'),
                __DIR__ . '/Models/TsTag.php' => base_path('app/Models/TsTag.php'),
                __DIR__ . '/Models/TsTasksHasTags.php' => base_path('app/Models/TsTasksHasTags.php'),
            ],
            'tasks-package'
        );
    }

    private function loadConfig()
    {
        $this->publishes([
            __DIR__.'/../config/task.php' => config_path('task.php'),
        ],'tasks-package');
    }
}