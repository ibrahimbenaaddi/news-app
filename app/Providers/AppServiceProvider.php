<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        Paginator::useBootstrapFive();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        // Gate for isAdmin
        Gate::define('isAdmin', function (Admin $admin) {
            return $admin?->isAdmin ? Response::allow() : Response::deny('You Are not Allowed to do this Action!!');
        });

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
