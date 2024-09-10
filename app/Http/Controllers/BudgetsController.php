<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BudgetsController extends Controller
{
    public function index(): View
    {   
        $budgets = Budget::all('title', 'active');
        return view('budgets.index', compact('budgets'));
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
        $request->user()->budgets()->create($validated);

        return to_route('budgets.index')->with('success', 'Budget created successfully.');
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

        return to_route('budgets.edit', $budget->id)->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget): View
    {
        $budget->delete();

        return view('budgets.index')->with('success', 'Budget deleted successfully.');
    }
}
