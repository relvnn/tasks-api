<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\User\Contracts\UserRepositoryInterface;
use App\Repositories\User\Concretes\UserRepository;

use App\Repositories\Task\Contracts\TaskRepositoryInterface;
use App\Repositories\Task\Concretes\TaskRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Tasks 
        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );
    }

    public function boot(): void
    {

    }
}
