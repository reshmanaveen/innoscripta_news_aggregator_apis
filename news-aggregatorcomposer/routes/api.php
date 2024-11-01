<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NewsFeedController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.reset');


Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    
        Route::get('/logout', [UserController::class, 'logout']);

        Route::get('/articles', [ArticleController::class, 'index']);
        Route::get('/articles/{id}', [ArticleController::class, 'show']);
        Route::get('/article/preferences', [ArticleController::class, 'getArticlePreferences']);
        Route::get('/preferences', [UserPreferenceController::class, 'index']);
        Route::post('/preferences', [UserPreferenceController::class, 'store']);
        Route::get('/news-feed', [NewsFeedController::class, 'index']);
    });