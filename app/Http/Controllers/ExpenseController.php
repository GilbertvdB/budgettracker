<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Receipt;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class ExpenseController extends Controller
{   
    public function index()
    {   
        //
    }

    public function show(Budget $budget): View
    {   
        $expenses = Receipt::where('budget_id', $budget->id)->latest()->get();

        return view('expenses.show', compact('budget', 'expenses'));
    }
    
    public function uploadReceipt(Request $request): JsonResponse
    {   
        // Validate the image input
        info($request->all());

        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2024', // Restricting file types and size
            'budget_id' => 'required', // Restricting file types and size
        ]);

        // Find the budget by ID
        $budget = Budget::find($request->budget_id);
        if (!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found',
            ], 404);
        }

        // Store the image in the 'receipts' directory in the public disk
        // $path = $request->file('receipt')->store('receipts', 'public');

        if ($request->hasFile('receipt')) {
            $filename = date('Y_m_d_His') . '_' . str_replace(' ', '', $request->file('receipt')->hashName());
            $request->file('receipt')->storePubliclyAs('images/receipts/', $filename, 'public');

            $receipt = new Receipt([
                'user_id' => Auth::id(),
                'budget_id' => $budget->id,
                'name' => $filename,
                'url' => 'images/receipts/' . $filename,
            ]);
            $receipt->save();

            $imagePath = public_path('storage/'.$receipt->url);
            info('img path: '.$imagePath);
    
            $processedReceiptText = $this->ocr($imagePath);
         
            $totalsArray = $this->extractTotalAmountFromString($processedReceiptText);
            info('totalArray:');
            info($totalsArray);
            $total = $totalsArray[0] ?? 0;
            info('total: '.$total);
            
            $receipt->ocr_text = $processedReceiptText;
            $receipt->total = $total;
            $receipt->save();
            
            $budget->rest_amount -= floatval($total);
            $budget->save();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Receipt file not found',
            ], 400);
        }


        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!',
            'receipt_total' => $receipt->total,
            'receipt_id' => $receipt->id,
        ], 200);
    }

    public function ocr($imagePath)
    {   
        try {

            $ocr = new TesseractOCR($imagePath);
            
            if (App::environment('local')) {
                $path = env('OCR_LOCAL');
                $ocr->executable($path);
            }
            
            $ocr->psm(6);
            $ocr->oem(3);
            $text = $ocr->run();

            return $text;

        } catch (Exception $e) {
            info('error :' . $e->getMessage());
        }
    }

    function extractTotalAmountFromString($text)
    {   
        info('init extract...');
        // Initialize an array to store the extracted totals
        $totals = [];

        // Pattern to match variations of "Total" or "Totaal" followed by an amount (xx,xx, x,xx, xx xx format)
        $pattern = '/\b(total|totaal)\s*[.:\-]?\s*[\€\$\£]?\s*(\d{1,3}(?:[.,\s]\d{3})*[.,]\d{2})\b/i';

        // Split the input text into lines
        $lines = explode("\n", $text);
        info('lines:');
        info($lines);

        // Loop through each line
        info('processing lines...');
        foreach ($lines as $line) {
            // Convert the line to lowercase to perform a case-insensitive check for "sub"
            $lowerLine = strtolower($line);

            // Check if the line matches the pattern and does not contain "sub"
            if (preg_match($pattern, $lowerLine, $matches) && strpos($lowerLine, 'sub') === false) {
                info('matching pattern and filtering...');
                // Extract the amount from the second capture group
                $amount = $matches[2];
                info('cleaning matches results...');
                // Remove any thousand separators (spaces or dots) and replace commas with periods
                $amount = str_replace(' ', '.', $amount);  // Replace spaces between numbers with periods
                $amount = str_replace(',', '.', $amount);  // Convert comma to period as decimal separator

                // Add the extracted amount to the totals array
                $totals[] = $amount;
            }
        }
        info('totals extracted:');
        info($totals);
        // Return the array of extracted totals, or null if no totals are found
        // return !empty($totals) ? $totals : "No matching totals found.";
        return !empty($totals) ? $totals : null;
    }


    public function getExpensesByBudget($budgetId)
    {
        // Fetch expenses for the selected budget
        $expenses = Receipt::where('budget_id', $budgetId)->latest()->get();

        // Return the expenses as a JSON response
        return response()->json([
            'expenses' => $expenses
        ]);
    }

    public function uploadTotalIncorrect(Request $request)
    {   
        $receipt = Receipt::find($request->receipt_id);
        $receipt->total_verified = 0;
        $receipt->save();

        Review::create([
            'receipt_id' => $receipt->id,
        ]);

        return to_route('dashboard')->with('success', 'Receipt in review!');
    }
}
