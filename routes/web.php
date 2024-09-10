<?php

use App\Http\Controllers\BudgetsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//     Route::get('/test', function () {
//         return view('welcome');
//     });
Route::get('/test', [DashboardController::class, 'test'])->name('dashboard.test');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/budgets', BudgetsController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/upload-receipt', [ExpenseController::class, 'uploadReceipt'])->name('upload.receipt');
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
});

require __DIR__.'/auth.php';
