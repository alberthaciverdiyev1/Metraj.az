<?php

namespace App\Providers;

use App\Core\Domain\Property\Repositories\PropertyRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Repositories\EloquentPropertyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PropertyRepositoryInterface::class,
            EloquentPropertyRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
