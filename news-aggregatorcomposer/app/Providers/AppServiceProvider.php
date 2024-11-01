<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\JsonResponse;

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

             // Use the macro for creating consistent JSON responses
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
