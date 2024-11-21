<?php

namespace App\Providers;

use App\Interfaces\TareaRepositoryInterface;
use App\Repositories\TareaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TareaRepositoryInterface::class,TareaRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
