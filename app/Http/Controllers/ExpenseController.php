<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadReceiptRequest;
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
use App\Services\ImageProcessingService;
use App\Services\OcrProcessingService;
use App\Services\ExtractTotalService;

class ExpenseController extends Controller
{   
    protected $imageProcessingService;
    protected $ocrProcessingService;
    protected $extractTotalService;

    public function __construct(ImageProcessingService $imageProcessingService, OcrProcessingService $ocrProcessingService, ExtractTotalService $extractTotalService )
    {
        $this->imageProcessingService = $imageProcessingService;
        $this->ocrProcessingService = $ocrProcessingService;
        $this->extractTotalService = $extractTotalService;
    }

    public function index()
    {   
        //
    }

    public function show(Budget $budget): View
    {   
        $expenses = Receipt::where('budget_id', $budget->id)->latest()->get();

        return view('expenses.show', compact('budget', 'expenses'));
    }
    
    public function uploadReceipt(UploadReceiptRequest $request): JsonResponse
    {   
        info($request->all());

        // Find the budget by ID
        $budget = Budget::find($request->budget_id);
        if (!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found',
            ], 404);
        }

        // Store the image in the 'receipts' directory in the public disk
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
        }

        // process the uploaded receipt
        if($receipt) {

            $this->processReceipt($receipt);

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

    public function processReceipt(Receipt $receipt)
    {
        // initiate srvices
        $processedImagePath = $this->imageProcessingService->preprocess($receipt->url);
        $processedReceiptText = $this->ocrProcessingService->processImagePath($processedImagePath);
        $totalsArray = $this->extractTotalService->extractTotalAmountFromString($processedReceiptText);

        info('totalArray:');
        info($totalsArray);
        $total = $totalsArray[0] ?? 0;
        info('total: '.$total);
        
        //update the receipt information
        $receipt->ocr_text = $processedReceiptText;
        $receipt->total = $total;
        $receipt->save();
        
        //update the budgets rest amount
        $receipt->budget->rest_amount -= floatval($total);
        $receipt->budget->save();

        // Clean up temporary files
        @unlink($processedImagePath);
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

    function extractTotalAmountFromString1($text)
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


    function extractTotalAmountFromString($text)
    {   
        info('init extract...');
        // Initialize an array to store the extracted totals
        $totals = [];

        // Pattern to match variations of "Total" or "Totaal" followed by an amount
        $patternTotal = '/\b(t[0-9a-z]{1,2}tal|total|totaal|lotaal|otaal|lataal)\s*[.:\-]?\s*[\€\$\£]?\s*(\d{1,3}(?:[.,\s]?\d{3})*[.,]\d{2})\b/iu';
        
        // Pattern to match variations of "Pinnen" or "Pin" followed by an amount
        $patternPin = '/\b(pinnen|pin)\s*[.:\-]?\s*[\€\$\£]?\s*(\d{1,3}(?:[.,\s]?\d{3})*[.,]\d{2})\b/iu';

        // Pattern to match "emballage" with variations (e.g., "emballase")
        $patternEmbalage = '/\b(embalage|emballase|embalges?)\b/i';

        // Split the input text into lines
        $lines = explode("\n", $text);
        info('lines:');
        info($lines);

        // Initialize a flag to track if emballage was found
        $embalageFound = false;

        // First pass: check for "emballage"
        foreach ($lines as $line) {
            if (preg_match($patternEmbalage, $line)) {
                info('emballage found in line...');
                $embalageFound = true; // Set the flag
                break; // Stop after finding the first occurrence
            }
        }

        // Second pass: if emballage was found, check for "pinnen"
        if ($embalageFound) {
            foreach ($lines as $line) {
                if (preg_match($patternPin, $line, $matches)) {
                    info('matching pattern for pin...');
                    // Extract the amount from the second capture group
                    $amount = $matches[2];
                    info('cleaning matches results...');
                    // Clean the amount
                    $amount = str_replace(' ', '.', $amount);  // Replace spaces between numbers with periods
                    $amount = str_replace(',', '.', $amount);  // Convert comma to period as decimal separator

                    // Add the extracted amount to the totals array
                    $totals[] = $amount;
                }
            }
        } 
        // If "emballage" was NOT found, look for "total"
        else {
            foreach ($lines as $line) {
                if (preg_match($patternTotal, $line, $matches) && strpos($line, 'sub') === false) {
                    info('matching pattern for total and filtering...');
                    // Extract the amount from the second capture group
                    $amount = $matches[2];
                    info('cleaning matches results...');
                    // Clean the amount
                    $amount = str_replace(' ', '.', $amount);  // Replace spaces between numbers with periods
                    $amount = str_replace(',', '.', $amount);  // Convert comma to period as decimal separator

                    // Add the extracted amount to the totals array
                    $totals[] = $amount;
                }
            }
        }

        info('totals extracted:');
        info($totals);
        
        // Return the array of extracted totals, or null if no totals are found
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
