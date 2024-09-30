<?php

namespace App\Http\Controllers;

use App\Mail\ShareBudgetInvite;
use App\Models\Budget;
use App\Models\ShareBudgetInvitation;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BudgetsController extends Controller
{
    public function index(): Void
    {   
        // $user = Auth::user();
        // $budgets = Budget::where('user_id', $user->id)->get();
        // $sharedBudgets = $user->budgets;

        // $b = Budget::where('user_id', $user->id)->get();
        // // dd($b->count());

        // return view('budgets.index', compact('budgets', 'sharedBudgets'));
    }

    public function create(): View
    {
        return view('budgets.create');
    }

    public function store(Request $request): RedirectResponse
    {   
        $validated = $request->validate([ 
            'title' => 'required|string|max:255',
            'amount' => 'required|min:1|decimal:0,2',
        ]);

        $validated['rest_amount'] = $request->amount;
        $validated['user_id'] = $request->user()->id;
        $budget = new Budget();
        $budget->create($validated);

        return to_route('dashboard')->with('success', 'Budget created successfully.');
    }

    public function edit(Budget $budget): View
    {   
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {   
        $validated = $request->validate([ 
            'title' => 'required|string|max:255',
            'amount' => 'required|min:1|decimal:0,2',
        ]);

        $budget->update($validated);

        return to_route('dashboard')->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget): RedirectResponse
    {       
        $files = $budget->receipts;
        if($files)
        {
            foreach($files as $file)
            {   
                Storage::disk('public')->delete($file->url);
            }
        }
        
        $budget->delete();

        return to_route('dashboard')->with('success', 'Budget deleted successfully.');
    }

    public function updateActiveStatus(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);
        
        // Update the active status
        $budget->active = $request->input('active');
        $budget->save();

        return redirect()->back()->with('success', 'Budget status updated!');
    }

    public function updatePinnedStatus(Request $request, $id)
    {   
        $user = Auth::user();

        // Check if the user has already pinned the budget
        // Pin the budget if not already pinned
        if (!$user->pinnedBudgets()->where('budget_id', $id)->exists()) 
        {
            $user->pinnedBudgets()->attach($id);

            return redirect()->back()->with('success', 'Budget has been pinned to the dashboard');
        } else 
        {
            // Unpin the budget if the user wants to unpin it
            if ($user->pinnedBudgets()->where('budget_id', $id)->exists()) {
                $user->pinnedBudgets()->detach($id);
            }

            return redirect()->back()->with('success', 'Budget has been unpinned from the dashboard');
        }

    }

    public function shareBudget(Budget $budget): View
    {
        return view('budgets.share-budget', compact('budget'));
    }

    public function ShareBudgetInvitation(Request $request, Budget $budget): RedirectResponse
    {   
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        //todo check if there is already a pending request for the budget from the user to user
        $checkShareRequest = ShareBudgetInvitation::where('budget_id', $budget->id)
                                                ->where('to_email', $request->email)
                                                ->where('from_user', Auth::id())
                                                ->get();
        
        if(!$checkShareRequest->isEmpty())
        {
            return redirect()->back()->with('success', 'A share invitation has already been sent!');
        }
        //else skip and return invation already sent.
        
        //register the share request
        $shareRequest = new ShareBudgetInvitation(); 
        $shareRequest->budget_id = $budget->id;
        $shareRequest->from_user = Auth::id();
        $shareRequest->to_email = $request->email;
        $shareRequest->status = 'created';
        
        //todo check if user is a registered user
        $invitedUser = User::where('email', $request->email)->first();

        if($invitedUser)
        {
            $shareRequest->to_user = $invitedUser->id;
        }

        //create a token
        $token = Str::random(24);
        $shareRequest->token = $token;
        info('======= Invitation token =======');
        info($token);
        $shareRequest->save();
        
        //dispathEvent send invited user a mail.

        // Generate the invitation link
        $inviteLink = route('budgets.invitation.accept', $token);

        // Optionally, send the invitation via email
        Mail::to($request->email)->send(new ShareBudgetInvite($inviteLink, Auth::user()->email, $budget->title));

        $shareRequest->status = 'mailed';
        $shareRequest->save();

        return to_route('dashboard')->with('success', 'Budget share e-mail invitation link send!');
    }

    public function acceptInvitation($token)
    {   
        // dd($token);
        // Find the invitation by token
        $invitation = ShareBudgetInvitation::where('token', $token)->first();
        $budget = $invitation->budget;
        $user = Auth::user();

        // Check if the token exists and hasn't already been accepted
        if (!$invitation || $invitation->status == 'accepted') {
            return redirect()->route('/')->with('error', 'Invalid or expired invitation.');
        }

        
        if(!$invitation->to_user)
        {   
            // Mark the invitation as accepted
            $invitation->status = 'registering';
            $invitation->save();
            // Redirect to the registration or invitation-acceptance page
            return redirect()->route('register')->with('message', 'Invitation accepted! Please complete your registration.');
        }

        $budget->users()->attach($user->id);

        // Mark the invitation as accepted
        $invitation->status = 'accepted';
        $invitation->save();
        
        // Optionally, remove the invitation after it's been accepted
        // $invitation->delete();
        
        return redirect()->route('dashboard')->with('message', 'Invitation accepted!');
    }

    public function share(Budget $budget): View
    {
        return view('budgets.share-budget', compact('budget'));
    }

    public function unshare(Budget $budget): View
    {   
        // dd($budget->users);
        return view('budgets.unshare', compact('budget'));
    }

    public function unshareBudget(Request $request, Budget $budget): RedirectResponse
    {   
        $user = User::find($request->email);
        $user->budgets()->detach($budget->id);
    
        return redirect()->route('dashboard')->with('success', 'No longer sharing with user!');
    }
}
