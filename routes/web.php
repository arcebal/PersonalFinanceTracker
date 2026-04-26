<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('landing');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Additional product routes: trashed, restore, force delete, export CSV
    Route::get('transactions/trashed', [TransactionController::class, 'trashed'])->name('transactions.trashed');
    Route::post('transactions/{id}/restore', [TransactionController::class, 'restore'])->name('transactions.restore');
    Route::delete('transactions/{id}/force', [TransactionController::class, 'forceDelete'])->name('transactions.forceDelete');
    Route::get('transactions/export/csv', [TransactionController::class, 'exportCsv'])->name('transactions.export');

    Route::get('accounts/trashed', [AccountController::class, 'trashed'])->name('accounts.trashed');
    Route::post('accounts/{id}/restore', [AccountController::class, 'restore'])->name('accounts.restore');
    Route::delete('accounts/{id}/force', [AccountController::class, 'forceDelete'])->name('accounts.forceDelete');
    Route::get('accounts/export/csv', [AccountController::class, 'exportCsv'])->name('accounts.export');

    Route::get('categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::get('categories/export/csv', [CategoryController::class, 'exportCsv'])->name('categories.export');
    Route::post('reports/export-pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.exportPdf');

    // Resource routes (placed after specific routes to avoid conflicts)
    Route::resource('categories', CategoryController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('budgets', BudgetController::class);

    // Profile routes (edit, update, destroy)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings/profile', [SettingsController::class, 'edit'])->name('settings.profile');
    Route::patch('/settings/profile/appearance', [SettingsController::class, 'updateAppearance'])->name('settings.appearance.update');
    Route::post('/settings/profile/avatar', [SettingsController::class, 'updateAvatar'])->name('settings.avatar.update');
    Route::delete('/settings/profile/avatar', [SettingsController::class, 'destroyAvatar'])->name('settings.avatar.destroy');
});

require __DIR__.'/auth.php';
