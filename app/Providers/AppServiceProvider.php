<?php

namespace App\Providers;

use App\Enums\PaymentType;
use App\Support\Services\IPayeable;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            IPayeable::class,
            function ($app) {
                $paymentMethodType = $app['request']->input('payment_type');

                return match ($paymentMethodType) {
                    PaymentType::METHOD_TWO->value => new \App\Support\Services\SecondMethod,
                    PaymentType::METHOD_ONE->value => new \App\Support\Services\FirstMethod,
                    default => throw new \InvalidArgumentException("Invalid payment method type"),
                };
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
