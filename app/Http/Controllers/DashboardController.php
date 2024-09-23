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

    public function test(): View
    {   
        $imgUrlPath = '..\storage\app\public';
        

        $img = 'http://budgettracker.test/storage/text.png';
        $im = '..\storage\app\public\text.png';
        $im1 = '..\storage\app\public\rcp1.jpeg';
        $ts1 = '..\storage\app\public\test1.jpeg';
        $ts2 = '..\storage\app\public\test2.jpeg';
        $ts3 = '..\storage\app\public\test3.jpeg';
        $ln1 = '..\storage\app\public\long1.jpeg';
        $ln2 = '..\storage\app\public\long2.jpeg';
        $ln3 = '..\storage\app\public\long3.jpeg';
        $ln4 = public_path('storage/long3.jpeg');
        $eto = '..\storage\app\public\etos.jpeg';
        
        $upload = Image::first();
        $upPath = public_path('storage/'.$upload->url);
        // dd($upload->url);
        // dd($image);
        $path = 'C:\Users\Gilbert\AppData\Local\Programs\Tesseract-OCR\tesseract.exe';
        // // dd($image);
        // echo (new TesseractOCR($im))->executable($path)->run();

        // $ocr = new TesseractOCR();
        // echo $ocr->version();
        // $ocr->executable($path);
        // $ocr->image($im);
        // $response = $ocr->run();

        // dd($response);
        try {
            $ocr = new TesseractOCR($upPath);
            $ocr->executable($path);
            $text = $ocr->run();

            // Define a filename
            // $fileName = 'ocr_output_' . time() . '.txt';

            // // Store the text in the storage/app/ocr directory
            // Storage::disk('local')->put('ocr/' . $fileName, $text);
            
            dd($text);
            $processed = $this->extractTotalAmountFromString($text);
            dd($processed);

        } catch (Exception $e) {
            dd('error :' . $e->getMessage());
        }

        return view('welcome');
    }

    public function test1()
    {   
        $fileName = 'ocr_output_4.txt';
        $filePath = Storage::disk('local')->path('ocr/' . $fileName);

        $list = $this->extractTotalAmountFromFile($filePath);
        $lines = [
            "Auterisatiecode 400089 Totaal 9,16 <UR"
        ];
        $data = $this->extractTotalsFromArray($lines);

        dd($data);
    }

    public function extractTotalAmountFromFile($filePath)
    {
        // Check if file exists
        if (!file_exists($filePath)) {
            return "File not found!";
        }

        // Initialize an array to store the matching lines
        $matchingLines = [];

        // Read the file line by line
        $file = fopen($filePath, "r");

        // Pattern to match "Totaal" followed by an amount (xx,xx format)
        // $pattern = '/Totaal\s*\d{1,3}(?:[.,]\d{2})?/i'; //case-incensitive
        $pattern = '/\bTotaal\s+(\d{1,3}[.,]\d{2})/i'; // totaal as a standalone word no SUBTOTAAL.

        // Loop through each line in the file
        while (($line = fgets($file)) !== false) {
            // Convert the line to lowercase to perform a case-insensitive check for "sub"
            $lowerLine = strtolower($line);

            // Check if the line matches the "Totaal" pattern and does not contain "sub"
            if (preg_match($pattern, $line) && strpos($lowerLine, 'sub') === false) {
                $matchingLines[] = trim($line); // Add matching line to the array if "sub" is not found
            }
        }

        // Close the file after reading
        fclose($file);

        // Return the matching lines, or a message if none were found
        return !empty($matchingLines) ? $matchingLines : "No matching lines found.";
    }

    public function extractTotalsFromArray($lines)
    {
        // Initialize an array to store the extracted totals
        $totals = [];

        // Regular expression pattern to match "Totaal" followed by an amount (xx,xx or xxx,xx)
        $pattern = '/\bTotaal\s+(\d{1,3}[.,]\d{2})/';

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
        $pattern = '/\bTotaal\s+(\d{1,3}[.,]\d{2})\b/i';

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
                
                // Remove commas and convert to proper float format (e.g., "43,16" -> "43.16")
                $amount = str_replace(',', '.', $matches[1]);

                // Add the extracted amount to the totals array
                $totals[] = $amount;
            }
        }

        // Return the array of extracted totals
        return !empty($totals) ? $totals : "No matching totals found.";
    }

}
