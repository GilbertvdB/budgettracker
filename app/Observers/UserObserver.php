<?php

namespace App\Observers;

use App\Models\ShareBudgetInvitation;
use App\Models\User;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class UserObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $invitations = ShareBudgetInvitation::where('to_email', $user->email)
                                            ->where('status', 'registering')
                                            ->get();

        foreach($invitations as $invitation)
        {
            $budget = $invitation->budget;
            $budget->users()->attach($user->id);

            $invitation->to_user = $user->id;
            $invitation->status = 'accepted';
            $invitation->save();
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
