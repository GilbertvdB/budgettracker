<?php

namespace App\Http\Controllers;

use App\Services\ImageProcessingService;
use App\Services\OcrProcessingService;
use App\Services\ExtractTotalService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminOcrController extends Controller
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
        $processedImagePath = $this->imageProcessingService->preprocess($imagePath);

        session(['processed_image' => $processedImagePath]);

        $text = $this->ocrProcessingService->processImagePath($processedImagePath);

        session(['ocr_text' => $text]);

        $total = $this->extractTotalService->extractTotalAmountFromString($text);

        session(['total' => $total]);

        // Clean up temporary files
        @unlink($processedImagePath);
        // @unlink(public_path('storage/'.$imagePath));

        return view('admin.ocrenv.show');
    }

}
