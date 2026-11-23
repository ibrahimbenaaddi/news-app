<?php

namespace App\Providers;

use App\Models\Admin;
use App\Traits\LogError;
use Exception;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    use LogError; // use to log the IP
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

        // Rate limiting the App
        RateLimiter::for('api-get-articles', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())->response(function (Request $request, array $headers) {
                self::theLog('rateLimiter', 'AppServiceProvider', new Exception('This Ip try to brute-force the App :'. $request->ip()));
                return response()->json(['message' => '429 Too Many Requests'], 429, $headers);
            });
        });
        RateLimiter::for('api-login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function (Request $request, array $headers) {
                self::theLog('rateLimiter', 'AppServiceProvider', new Exception('This Ip try to brute-force the App :'. $request->ip()));
                return response()->json(['message' => '429 Too Many Requests'], 429, $headers);
            });
        });
        RateLimiter::for('api-admin-article', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip())->response(function (Request $request, array $headers) {
                self::theLog('rateLimiter', 'AppServiceProvider', new Exception('This Ip try to brute-force the App :'. $request->ip()));
                return response()->json(['message' => '429 Too Many Requests'], 429, $headers);
            });
        });
    }
}
