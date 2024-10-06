<?php

namespace App\Http\Controllers;

use App\Services\ImageProcessingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Exception;
use Illuminate\Support\Facades\App;

class AdminOcrController extends Controller
{
    protected $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->imageProcessingService = $imageProcessingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.ocrenv.index');
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
    public function uploadImage(Request $request)
    {
        $request->validate([
            'compressed_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('compressed_image')) {
            $file = $request->file('compressed_image');
            $path = $file->store('uploads', 'public'); // Store the file

            // Store the path in the session
            session(['compressed_image_path' => $path]);

            return response()->json(['message' => 'Image uploaded successfully!', 'path' => $path]);
        }
    
        return response()->json(['error' => 'No image uploaded.'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $imagePath = session('compressed_image_path');
        // Preprocess the image
        $processedImagePath = $this->imageProcessingService->preprocess(public_path('storage/'.$imagePath));

        session(['processed_image' => $processedImagePath]);

        $text = $this->ocr($processedImagePath);

        session(['ocr_text' => $text]);

        $total = $this->extractTotalAmountFromString($text);

        session(['total' => $total]);

        // Clean up temporary files
        @unlink($processedImagePath);
        // @unlink(public_path('storage/'.$imagePath));

        return view('admin.ocrenv.show');
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
    public function destroy(string $id)
    {
        //
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
        $pattern = '/\b(t[0-9a-z]{1,2}tal|total|totaal|lotaal|otaal)\s*[.:\-]?\s*[\€\$\£]?\s*(\d{1,3}(?:[.,\s]?\d{3})*[.,]\d{2})\b/iu';

        // Split the input text into lines
        $lines = explode("\n", $text);
        info('lines:');
        info($lines);

        // Loop through each line
        info('processing lines...');
        foreach ($lines as $line) {

            // Check if the line matches the pattern and does not contain "sub"
            if (preg_match($pattern, $line, $matches) && strpos($line, 'sub') === false) {
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



}
