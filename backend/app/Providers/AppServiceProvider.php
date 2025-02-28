<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TravelOrders\TravelOrdersRepository;
use App\Repositories\TravelOrders\TravelOrdersRepositoryInterface;
use App\Services\Notification\NotificationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TravelOrdersRepositoryInterface::class, TravelOrdersRepository::class);
        
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
