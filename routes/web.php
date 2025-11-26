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

    // Scan QR Code page
    Route::get('/spt/{id}', \App\Livewire\Public\Scan\Detail::class)->name('scan.spt');
    Route::get('/sppd/{id}', \App\Livewire\Public\Scan\Detail::class)->name('scan.sppd');

    // Login
    Route::get('/login', Login::class)->name('login');
});

// Logout route (available for authenticated users)
Route::get('/logout', function () {
    session()->flush();
    auth()->logout();
    return redirect()->route('home')->with('success', 'Berhasil logout');
})->name('logout');

// ========================================
// ADMIN ROUTES (Protected)
// ========================================
// Route::middleware(['web'])
Route::middleware(['web', 'auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // Surat Perintah Management
        Route::prefix('surat-perintah')->name('surat-perintah.')->group(function () {
            Route::get('/', \App\Livewire\Admin\SuratPerintah\Index::class)->name('index');
            Route::get('/create', \App\Livewire\Admin\SuratPerintah\Form::class)->name('create');
            Route::get('/{id}/edit', \App\Livewire\Admin\SuratPerintah\Form::class)->name('edit');
            Route::get('/{id}/sppd', \App\Livewire\Admin\SuratPerintah\Sppd::class)->name('sppd');
            Route::get('/{id}/preview', \App\Livewire\Admin\SuratPerintah\Preview::class)->name('preview');
        });

        // SPPD Management
        Route::prefix('sppd')->name('sppd.')->group(function () {
            Route::get('/', SppdList::class)->name('index');
            Route::get('/create', SppdForm::class)->name('create');
            Route::get('/{id}/edit', SppdForm::class)->name('edit');
            Route::get('/{id}/preview', \App\Livewire\Admin\SPPD\Preview::class)->name('preview');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Reports\SppdReports::class)->name('sppd');
        });

        // Laravel Impersonate routes
        Route::impersonate();
    });
