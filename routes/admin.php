<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::name('admin.')->prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/test', function () {
        return view('welcome');
    });

    Route::resource('users', AdminUserController::class);
    Route::resource('reviews', ReviewController::class);
    Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::get('feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
    Route::delete('feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');
});