<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Admin\SppdList;
use App\Livewire\Admin\SppdForm;
use App\Livewire\Home;
use App\Livewire\Auth\Login;

// ========================================
// PUBLIC ROUTES
// ========================================
Route::middleware(['web', App\Http\Middleware\RedirectIfAuthenticated::class])->group(function () {
    // Home page
    Route::get('/', Home::class)->name('home');

    // Login
    Route::get('/login', Login::class)->name('login');
});

// Logout route (available for authenticated users)
Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('home')->with('success', 'Berhasil logout');
})->name('logout');

// ========================================
// ADMIN ROUTES (Protected)
// ========================================
Route::middleware(['web', 'auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // SPPD Management
        Route::prefix('sppd')->name('sppd.')->group(function () {
            Route::get('/', SppdList::class)->name('index');
            Route::get('/create', SppdForm::class)->name('create');
            Route::get('/{id}/edit', SppdForm::class)->name('edit');
        });

        // Laravel Impersonate routes
        Route::impersonate();
    });
