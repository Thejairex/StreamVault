<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Webhook de Stripe (sin auth ni middleware de suscripción)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');

// Checkout de suscripción (requiere auth pero NO suscripción activa)
Route::middleware('auth')->group(function () {
    Route::get('/subscription/checkout', [SubscriptionController::class, 'checkout'])
        ->name('subscription.checkout');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscription.cancel');
});

// Rutas que requieren auth + suscripción activa
Route::middleware(['auth', \App\Http\Middleware\EnsureSubscribed::class])->group(function () {
    Route::get('/streamer/register', \App\Livewire\Streamer\Register::class)
        ->name('streamer.register');
    Route::get('/streamer/dashboard', \App\Livewire\Streamer\Dashboard::class)
        ->name('streamer.dashboard');
});
