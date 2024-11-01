<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Response::macro('success', function ($data = null, $message = null, $status = 200) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => $message,
            ], $status);
        });

        Response::macro('error', function ($message = 'An error occurred', $status = 400) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], $status);
        });
    }
}
