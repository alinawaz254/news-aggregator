<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\UserPreferenceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password-reset', 'resetPassword');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});

// Article Routes
Route::controller(ArticleController::class)->group(function () {
    Route::get('articles', 'getArticles');
    Route::get('articles/{id}', 'getArticle');
});

// User Preference Routes
Route::middleware('auth:sanctum')->prefix('user')
    ->controller(UserPreferenceController::class)->group(function () {
    Route::get('preferences', 'getPreferences');
    Route::post('preferences', 'setPreferences');
    Route::get('personalized-feed', 'getPersonalizedFeed');
});
