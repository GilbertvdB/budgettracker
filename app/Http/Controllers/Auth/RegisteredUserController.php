<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShareBudgetInvitation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        //todo dispatch event or service checkPendingInvitations to check if user has a pending share invitation
        $invitations = ShareBudgetInvitation::where('to_email', $user->email)
                                            ->where('status', 'registering')
                                            ->get();
        // dd($invitations);
        foreach($invitations as $invitation)
        {
            $budget = $invitation->budget;
            $budget->users()->attach($user->id);

            $invitation->to_user = $user->id;
            $invitation->status = 'accepted';
            $invitation->save();
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
