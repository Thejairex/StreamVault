<?php

use App\Livewire\Subscription\Checkout;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    // ─── Checkout de suscripción (accesible sin suscripción activa) ───
    Route::livewire('subscription/checkout', Checkout::class)->name('subscription.checkout');
});

Route::middleware(['auth', 'verified', 'subscribed'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
