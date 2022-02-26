<?php

namespace App\Providers;

use App\Contracts\Repositories\FileRepositoryInterface;
use App\Contracts\Repositories\GroupRepositoryInterface;
use App\Contracts\Repositories\PostitRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Repositories\FileRepository;
use App\Repositories\UserRepository;
use App\Repositories\GroupRepository;
use App\Repositories\PostitRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(PostitRepositoryInterface::class, PostitRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
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
