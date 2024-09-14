<?php

namespace App\Observers;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetObserver
{
    /**
     * Handle the Budget "created" event.
     */
    public function created(Budget $budget): void
    {
        $user = Auth::user(); // Get the currently authenticated user
        $userBudgetRecords = Budget::where('user_id', $user->id)->get();

        // Check if this is the only budget the user has
        if ($userBudgetRecords->count() <= 1) {
            if (!$user->pinnedBudgets()->where('budget_id', $budget->id)->exists()) 
            {
                $user->pinnedBudgets()->attach($budget->id);
            }
        }
    }

    /**
     * Handle the Budget "updated" event.
     */
    public function updated(Budget $budget): void
    {
        //
    }

    /**
     * Handle the Budget "deleted" event.
     */
    public function deleted(Budget $budget): void
    {   
        $user = Auth::user(); // Get the currently authenticated user
        $userBudgetRecords = Budget::where('user_id', $user->id)->get();
        
        // Check if this is the only budget the user has
        if ($userBudgetRecords->count() == 1) {
            $firstBudget = Budget::where('user_id', $user->id)->first();
            if (!$user->pinnedBudgets()->where('budget_id', $firstBudget->id)->exists()) 
            {
                $user->pinnedBudgets()->attach($firstBudget->id);
            }
        }
    }

    /**
     * Handle the Budget "restored" event.
     */
    public function restored(Budget $budget): void
    {
        //
    }

    /**
     * Handle the Budget "force deleted" event.
     */
    public function forceDeleted(Budget $budget): void
    {
        //
    }
}
