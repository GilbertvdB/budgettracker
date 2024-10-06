<?php

namespace App\Services;


class ExtractTotalService
{

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