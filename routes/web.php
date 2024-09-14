<?php

use App\Http\Controllers\BudgetsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/slider', function () {
    return view('budgets.partials.slider');
});

Route::get('/test', [DashboardController::class, 'test'])->name('dashboard.test');
Route::get('/budgets/invitation/accept/{token}', [BudgetsController::class, 'acceptInvitation'])->name('budgets.invitation.accept');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/budgets', BudgetsController::class);
    Route::patch('/budgets/{id}/update-active', [BudgetsController::class, 'updateActiveStatus'])->name('budgets.updateActiveStatus');
    Route::patch('/budgets/{id}/update-pinned', [BudgetsController::class, 'updatePinnedStatus'])->name('budgets.updatePinnedStatus');
    Route::get('/budgets/{budget}/share-budget', [BudgetsController::class, 'shareBudget'])->name('budgets.shareBudget');
    Route::post('/budgets/{budget}/share-budget', [BudgetsController::class, 'shareBudgetInvitation'])->name('budgets.shareBudgetInvitation');
    

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/upload-receipt', [ExpenseController::class, 'uploadReceipt'])->name('upload.receipt');
    Route::get('/expenses/{budget}', [ExpenseController::class, 'getExpensesByBudget']);
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
});

// Route::get('/mailable', function () {
//     $budget = App\Models\Budget::find(1);
//     $token = 'placeholdertokengoeshere';
//     $inviteLink = route('budgets.invitation.accept', $token);
//     $fromEmail = 'admin@mail.com';
//     $toEmail = 'jane@mail.com';
 
//     return new App\Mail\ShareBudgetInvite($inviteLink, $fromEmail, $budget->title);
// });

require __DIR__.'/auth.php';
