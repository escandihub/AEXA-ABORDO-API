<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Services\CustomerService;
use App\Services\PaymentService;
use App\Services\OpenPayWebhookService;

use App\Services\TransactionService;
use App\Services\PaymentServices\ErrorHandler;
use App\Services\PaymentServices\UpdateTransaction;
use App\Services\PaymentServices\TransactionStatusService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CustomerService::class, function ($app) {
            return new CustomerService();
        });
        $this->app->singleton(TransactionStatusService::class, function ($app) {
            return new TransactionStatusService();
        });
        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService();
        });
        $this->app->singleton(OpenPayWebhookService::class, function ($app) {
            return new OpenPayWebhookService(
                $app->make(TransactionService::class),
                $app->make(ErrorHandler::class),
                $app->make(UpdateTransaction::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', function(User $user){
            return $user->isAdmin() == "admin";
        });
        Gate::define('isGerente', function(User $user){
            return $user->isAdmin() == "gerente";
        });
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Schema::defaultStringLength(191);
    }
}
