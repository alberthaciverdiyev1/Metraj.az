<?php

namespace App\Providers;

use App\Modules\Agency\Repositories\AgencyRepositoryInterface;
use App\Modules\Agency\Repositories\AgentRepositoryInterface;
use App\Modules\Blog\Repositories\BlogRepositoryInterface;
use App\Modules\Inquiry\Repositories\InquiryRepositoryInterface;
use App\Modules\Property\Repositories\PropertyRepositoryInterface;
use App\Modules\Agency\Repositories\AgencyRepository;
use App\Modules\Agency\Repositories\AgentRepository;
use App\Modules\Blog\Repositories\BlogRepository;
use App\Modules\Inquiry\Repositories\InquiryRepository;
use App\Modules\Property\Repositories\PropertyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PropertyRepositoryInterface::class,
            PropertyRepository::class
        );

        $this->app->bind(
            AgencyRepositoryInterface::class,
            AgencyRepository::class
        );

        $this->app->bind(
            AgentRepositoryInterface::class,
            AgentRepository::class
        );

        $this->app->bind(
            BlogRepositoryInterface::class,
            BlogRepository::class
        );

        $this->app->bind(
            InquiryRepositoryInterface::class,
            InquiryRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
