<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageProcessingService
{
    public function preprocess($receiptUrl)
    {   
        //create the imagePath from the receipt Url
        $imagePath = public_path('storage/'.$receiptUrl);
        info('img path: '.$imagePath);
        
        // Load the image
        $image = imagecreatefromjpeg($imagePath); // Change this based on the image type

        // Step 1: Convert to Grayscale
        imagefilter($image, IMG_FILTER_GRAYSCALE);

        // Step 2: Apply Adaptive Thresholding (simple thresholding for example purposes)
        $this->applyThreshold($image);

        // Step 3: Noise Reduction (using Gaussian Blur)
        imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);

        // Step 4: Skew Correction (Note: this requires external libraries like OpenCV)
        // For now, we can skip this or implement it later with OpenCV.
        // Example pseudocode for skew correction with OpenCV
        // Load the image with OpenCV
        // $image = cv::imread($imagePath);
        // Use OpenCV functions to find lines and calculate skew angle
        // $angle = calculateSkew($image);
        // Rotate the image based on the calculated angle
        // $correctedImage = rotateImage($image, -$angle);
        // Save or return the corrected image

        // Save the processed image to a temporary file
        $processedImagePath = tempnam(sys_get_temp_dir(), 'processed_') . '.jpg';
        imagejpeg($image, $processedImagePath);

        // Free up memory
        imagedestroy($image);

        return $processedImagePath;
    }

    private function applyThreshold($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Loop through each pixel and apply simple thresholding
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                // Calculate brightness
                $brightness = ($r + $g + $b) / 3;

                // Apply threshold (adjust threshold value as needed)
                $newColor = $brightness > 128 ? 255 : 0;
                $color = imagecolorallocate($image, $newColor, $newColor, $newColor);
                imagesetpixel($image, $x, $y, $color);
            }
        }
    }
}