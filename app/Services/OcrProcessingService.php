<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;
use Exception;
use Illuminate\Support\Facades\App;

class OcrProcessingService
{

    public function processImagePath($imagePath)
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
            info('ocr error :' . $e->getMessage());
        }
    }


}