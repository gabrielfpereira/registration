<?php

namespace App\Providers;

use App\Models\{Registration, User};
use App\Policies\RegistrationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('supervisor', function (User $user) {
            return $user->type == 'supervisor';
        });

        Gate::policy(Registration::class, RegistrationPolicy::class);
    }
}
