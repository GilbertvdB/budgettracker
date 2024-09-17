<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
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
    
    public function uploadReceipt(Request $request, $id)
    {   
        // Validate the image input
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096', // Restricting file types and size
        ]);

        info($request->all());
        // Find the budget by ID
        $budget = Budget::find($id);
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
    
            $totalsArray = $this->ocr($imagePath);
            $total = $totalsArray[0] ?? 0;
            info('total: '.$total);
    
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
            
            $text = $ocr->run();
            
            // dd($text);
            $processed = $this->extractTotalAmountFromString($text);
            if($processed == "No matching totals found.")
            { 
                info($processed); 
            }

            return $processed;

        } catch (Exception $e) {
            info('error :' . $e->getMessage());
        }
    }

    public function extractTotalsFromArray($lines)
    {
        // Initialize an array to store the extracted totals
        $totals = [];

        // Regular expression pattern to match "Totaal" followed by an amount (xx,xx or xxx,xx)
        $pattern = '/\bTotaal\s+(\d{1,3}[.,]\d{2})/i';

        // Loop through each line in the array
        foreach ($lines as $line) {
            // Use preg_match to find the pattern and capture the amount
            if (preg_match($pattern, $line, $matches)) {
                // Remove commas and convert to proper float format (e.g. "43,16" -> "43.16")
                $amount = str_replace(',', '.', $matches[1]);

                // Add the extracted amount to the totals array
                $totals[] = $amount;
            }
        }

        // Return the array of extracted totals
        return $totals;
    }

    function extractTotalAmountFromString($text)
    {
        // Initialize an array to store the extracted totals
        $totals = [];

        // Pattern to match "Totaal" followed by an amount (xx,xx format)
        // Note: Adjusted to handle both ',' and '.' as decimal separators
        $pattern = '/\bTotaal\s+(\d{1,3}\s*\d{0,2}[.,]?\d{2})\b/i';

        // Split the input text into lines
        $lines = explode("\n", $text);

        // Loop through each line
        foreach ($lines as $line) {
            // Convert the line to lowercase to perform a case-insensitive check for "sub"
            $lowerLine = strtolower($line);

            // Check if the line matches the "Totaal" pattern and does not contain "sub"
            if (preg_match($pattern, $line) && strpos($lowerLine, 'sub') === false) {
                // Extract the amount
                preg_match($pattern, $line, $matches);
                
                $amount = $matches[1];

                // Remove any spaces and replace commas with periods
                $amount = str_replace(' ', '.', $amount); // Remove all spaces
                $amount = str_replace(',', '.', $amount); // Replace comma with period

                // Add the extracted amount to the totals array
                $totals[] = $amount;
            }
        }

        // Return the array of extracted totals
        return !empty($totals) ? $totals : "No matching totals found.";
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
}
