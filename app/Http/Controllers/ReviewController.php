<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $reviews = Review::paginate(10);
        return view('admin.review.index', compact('reviews'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review): View
    {   
        return view('admin.review.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {   
        $receipt = Receipt::findOrFail($review->receipt_id);
        $budget = $receipt->budget;

        //Correct the budget rest total
        $difference = floatval($request->total) - floatval($receipt->total);
        $budget->rest_amount -= $difference;
        $budget->save();
        
        //Update the receipt
        $receipt->total = floatval($request->total);
        $receipt->total_verified = 1;
        $receipt->save();

        //Update the receipt review
        $review->status = 'completed';
        $review->save();

        return to_route('admin.reviews.index')->with(['success' => 'Review has been updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return to_route('admin.reviews.index')->with(['success' => 'Review has been deleted!']);
    }
}
