<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);
    
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans/store', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/loans/update/{id}', [LoanController::class, 'update'])->name('loans.update');
    Route::post('/loans/approve/{id}', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/reject/{id}', [LoanController::class, 'reject'])->name('loans.reject');
    Route::delete('/loans/{id}', [LoanController::class, 'destroy'])->name('loans.destroy');

    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::post('/returns/store', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/returns/update/{id}', [ReturnController::class, 'update'])->name('returns.update');
    Route::post('/returns/approve/{id}', [ReturnController::class, 'approve'])->name('returns.approve');
    Route::post('/returns/reject/{id}', [ReturnController::class, 'reject'])->name('returns.reject');
    Route::delete('/returns/{id}', [ReturnController::class, 'destroy'])->name('returns.destroy');
});

require __DIR__.'/auth.php';