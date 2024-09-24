<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Image;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;

class DashboardController extends Controller
{
    public function index(): View
    {   
        $user = Auth::user();
        // $pinnedBudgets = Auth::user()->pinnedBudgets()->get(['title', 'amount', 'rest_amount']);
        $userBudgets = Budget::where('user_id', Auth::id())->get();
        $sharedBudgets = $user->budgets;
        $budgets = $userBudgets->merge($sharedBudgets)->sortByDesc('created_at');

        return view('dashboard', compact('budgets'));
    }
}
