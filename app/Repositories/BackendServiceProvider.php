<?php
namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Repositories\v1\TestRepositoryInterface',
            'App\Repositories\v1\TestRepository',

            'App\Repositories\v1\AuthRepositoryInterface',
            'App\Repositories\v1\AuthRepository',

            'App\Repositories\v1\AuthRepositoryInterface',
            'App\Repositories\v1\BooksRepository',

            'App\Repositories\v1\SystemRepositoryInterface',
            'App\Repositories\v1\SystemRepository',
        );
    }
}
