<?php

use App\Http\Controllers\ApiBudgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Models\User;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/user/{id}', function (string $id) {
    return new UserResource(User::findOrFail($id));
});

Route::get('/users', function () {
    return UserResource::collection(User::all());
});

Route::get('/budgets/{userId}', [ApiBudgetController::class, 'show']);
Route::post('/budgets', [ApiBudgetController::class, 'store']);
Route::put('/budgets/{id}', [ApiBudgetController::class, 'update']);
Route::delete('/budgets/{id}', [ApiBudgetController::class, 'destroy']);
Route::post('/budgets/share/{id}', [ApiBudgetController::class, 'shareBudget']);
Route::post('/budgets/unshare/{id}', [ApiBudgetController::class, 'unshareBudget']);
//invitation accept route
Route::get('/budgets/receipts/{id}', [ApiBudgetController::class, 'getReceipts']);
