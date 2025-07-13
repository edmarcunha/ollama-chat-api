<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Api\ChatController;

// PUBLIC ROUTES
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('/reset-password', [NewPasswordController::class, 'store']);

// AUTH ROUTES
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store']);
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->name('verification.verify');
    
    Route::apiResource('users', UserController::class)->except(['store']);

    Route::apiResource('chats', ChatController::class)->except(['update']);
    Route::post('/chats/{chat}/message', [ChatController::class, 'sendMessage']);
});