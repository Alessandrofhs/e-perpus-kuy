<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);
    
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/{id}', [LoanController::class, 'show'])->name('loans.edit');
    Route::post('/loans/store', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/loans/update/{id}', [LoanController::class, 'update'])->name('loans.update');
    Route::post('/loans/approve/{id}', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/reject/{id}', [LoanController::class, 'reject'])->name('loans.reject');
    Route::delete('/loans/{id}', [LoanController::class, 'destroy'])->name('loans.destroy');

    Route::prefix('returns')->middleware('auth')->group(function () {
        Route::get('/',        [ReturnController::class, 'index'])->name('returns.index');
        Route::post('/store',  [ReturnController::class, 'store'])->name('returns.store');
    });
});

require __DIR__.'/auth.php';