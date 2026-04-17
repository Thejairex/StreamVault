<?php

use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionController;
use App\Livewire\Subscription\Checkout;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::livewire('subscription/checkout', Checkout::class)->name('subscription.checkout');
});

Route::middleware(['auth', 'verified', 'subscribed'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});


Route::post('stripe/webhook', [StripeWebhookController::class,'handle'])->name('stripe.webhook');
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/checkout', [SubscriptionController::class,'checkout'])->name('subscription.checkout');
    Route::get('/subscription/cancel', [SubscriptionController::class,'cancel'])->name('subscription.cancel');
});
require __DIR__.'/settings.php';
require __DIR__.'/streamer.php';
