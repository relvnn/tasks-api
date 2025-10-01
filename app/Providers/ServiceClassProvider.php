<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

// Services
use App\Services\Concretes\AuthService;
use App\Services\Concretes\UserService;
use App\Services\Task\Concretes\TaskService;


// Interfaces
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Task\Contracts\TaskServiceInterface;

class ServiceClassProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Serviço responsável pela lógica de autenticação
        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class
        );

        // Serviço responsável pela lógica de usuários
        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );

        // Serviço responsável pela lógica de tarefas
        $this->app->bind(
            TaskServiceInterface::class,
            TaskService::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
