<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
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
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);
    Route::resource('profiles', ProfileController::class);
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/loans/approve/{id}', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/reject/{id}', [LoanController::class, 'reject'])->name('loans.reject');
    Route::put('/loans/{id}', [LoanController::class, 'update'])->name('loans.update');
    Route::delete('/loans/{id}', [LoanController::class, 'destroy'])->name('loans.destroy');
});

require __DIR__.'/auth.php';