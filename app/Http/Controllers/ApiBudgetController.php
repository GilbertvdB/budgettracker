<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BudgetResource;
use App\Http\Resources\ReceiptResource;
use App\Mail\ShareBudgetInvite;
use App\Models\Budget;
use App\Models\Receipt;
use App\Models\ShareBudgetInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiBudgetController extends Controller
{
    public function show(String $userId)
    {   
        $user = User::find($userId);
        Auth::login($user);

        $userBudgets = Budget::where('user_id', $userId)->get();
        $sharedBudgets = $user->budgets;
        $budgets = $userBudgets->merge($sharedBudgets)->sortByDesc('created_at');

        return BudgetResource::collection($budgets);   
    }

    public function store(Request $request): JsonResponse
    {  
        $validated = $request->validate([ 
            'title' => 'required|string|max:255',
            'amount' => 'required|min:1|decimal:0,2',
        ]);

        $validated['rest_amount'] = $request->amount;
        // $validated['user_id'] = $request->user()->id;
        $validated['user_id'] = 3;
        $budget = new Budget();
        $budget->create($validated);
    
        return response()->json([
            'success' => true,
            'message' => 'Budget added successfully!',
        ], 200);    
    }

    public function update(Request $request, String $budgetId): JsonResponse
    {   
        $validated = $request->validate([ 
            'title' => 'required|string|max:255',
            'amount' => 'required|min:1|decimal:0,2',
        ]);

        $budget = Budget::findorfail($budgetId);

        $budget->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Budget updated successfully!',
        ], 200);
    }

    public function destroy(String $budgetId): JsonResponse
    {   
        $budget = Budget::findorfail($budgetId);

        $files = $budget->receipts;
        if($files)
        {
            foreach($files as $file)
            {   
                Storage::disk('public')->delete($file->url);
            }
        }
        
        $budget->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Budget deleted successfully!',
        ], 200);
    }

    public function shareBudget(Request $request, String $budgetId): JsonResponse
    {   
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        $budget = Budget::findorfail($budgetId);
        $user = User::find($budget->user_id);
        Auth::login($user);

        //todo check if there is already a pending request for the budget from the user to user
        $checkShareRequest = ShareBudgetInvitation::where('budget_id', $budget->id)
                                                ->where('to_email', $request->email)
                                                ->where('from_user', Auth::id())
                                                ->get();
        
        if(!$checkShareRequest->isEmpty())
        {
            return response()->json([
                'success' => true,
                'message' => 'Budget share invitation already sent!',
            ], 200);
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
        info($inviteLink);

        // Optionally, send the invitation via email
        Mail::to($request->email)->send(new ShareBudgetInvite($inviteLink, Auth::user()->email, $budget->title));

        $shareRequest->status = 'mailed';
        $shareRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Budget share invitation sent!',
        ], 200);
    }

    public function acceptInvitation()
    {
        //
    }

    public function unshareBudget(Request $request, String $budgetId): JsonResponse
    {   
        info($request->email);
        $budget = Budget::findorfail($budgetId);
        $user = User::where('email', $request->email)->first();
        $user->budgets()->detach($budget->id);
        
        return response()->json([
            'success' => true,
            'message' => 'No longer sharing with user!',
        ], 200);
    }

    public function getReceipts(String $budgetId)
    {   
        $budget = Budget::findorfail($budgetId);
        $receipts = Receipt::where('budget_id', $budget->id)->latest()->get();
        // $user = User::find($userId);
        // Auth::login($user);

        return ReceiptResource::collection($receipts); 
    }
}
