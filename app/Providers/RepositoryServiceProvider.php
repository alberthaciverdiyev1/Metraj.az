<?php

namespace App\Providers;

use App\Modules\Agency\Repositories\AgencyRepositoryInterface;
use App\Modules\Agency\Repositories\AgentRepositoryInterface;
use App\Modules\Blog\Repositories\BlogRepositoryInterface;
use App\Modules\Inquiry\Repositories\InquiryRepositoryInterface;
use App\Modules\Property\Repositories\PropertyRepositoryInterface;
use App\Modules\Agency\Repositories\EloquentAgencyRepository;
use App\Modules\Agency\Repositories\EloquentAgentRepository;
use App\Modules\Blog\Repositories\EloquentBlogRepository;
use App\Modules\Inquiry\Repositories\EloquentInquiryRepository;
use App\Modules\Property\Repositories\EloquentPropertyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PropertyRepositoryInterface::class,
            EloquentPropertyRepository::class
        );

        $this->app->bind(
            AgencyRepositoryInterface::class,
            EloquentAgencyRepository::class
        );

        $this->app->bind(
            AgentRepositoryInterface::class,
            EloquentAgentRepository::class
        );

        $this->app->bind(
            BlogRepositoryInterface::class,
            EloquentBlogRepository::class
        );

        $this->app->bind(
            InquiryRepositoryInterface::class,
            EloquentInquiryRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
