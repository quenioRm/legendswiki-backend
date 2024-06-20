<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ApiRegisteredUserController;
use App\Http\Controllers\Api\Auth\ApiAuthenticatedSessionController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\SubitemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [ApiRegisteredUserController::class, 'store'])
                ->middleware('guest')
                ->name('api.register');

Route::post('/login', [ApiAuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('api.login');

Route::post('/logout', [ApiAuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth:sanctum')
                ->name('api.logout');

Route::post('/refresh', [ApiAuthenticatedSessionController::class, 'refresh'])
                ->middleware('auth:sanctum')
                ->name('api.refresh');

// Route::post('/forgot-password', [ApiPasswordResetLinkController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('api.password.email');

// Route::post('/reset-password', [ApiNewPasswordController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('api.password.store');

// Route::get('/verify-email/{id}/{hash}', [ApiVerifyEmailController::class, 'verify'])
//                 ->middleware(['auth', 'signed', 'throttle:6,1'])
//                 ->name('api.verification.verify');

// Route::post('/email/verification-notification', [ApiEmailVerificationNotificationController::class, 'store'])
//                 ->middleware(['auth', 'throttle:6,1'])
//                 ->name('api.verification.send');


// Rotas públicas para obter tópicos e subitems
Route::get('/topics', [TopicController::class, 'index']);
Route::get('/topics/{topic}/subitems', [SubitemController::class, 'index']);

// Rotas protegidas por middleware de autenticação e administração
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('topics', TopicController::class)->except(['index']);
    Route::prefix('topics/{topic}')->group(function () {
        Route::apiResource('subitems', SubitemController::class)->except(['index']);
    });
});
