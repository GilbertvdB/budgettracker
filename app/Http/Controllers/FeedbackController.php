<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $feedbacks = Feedback::paginate(10);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {   
        $validated = $request->validate([
            'message' => 'required|max:255',
        ]);

        Feedback::create(['user_id' => Auth::id(), 
                          'message' => $request->message]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback send!',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback): View
    {   
        return view('admin.feedbacks.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return to_route('admin.feedbacks.index')->with('success', 'Feedback deleted!');
    }
}
