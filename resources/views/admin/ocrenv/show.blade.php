<x-admin-layout>
    <div class="max-w-6xl mx-auto py-2 px-4 sm:px-6 lg:px-8">

        <div class="container flex flex-col sm:flex-row sm:flex-wrap border">
            <!-- Left Column (Receipt Image) -->
            <div class="w-full sm:w-1/2 border box-border">
                <div class="receipt-image border">
                @if(session('compressed_image_path'))
                    <img src="{{ asset('storage/' . session('compressed_image_path')) }}" alt="Uploaded Image" class="w-full" />
                @endif
                </div>
                <div class="receipt-image2 border">
                @if(session('processed_image'))
                    <img src="{{ asset('storage/' . session('processed_image')) }}" alt="Processed Image" class="w-full" />
                @endif
                </div>
            </div>

            <!-- OCR Text Column -->
            <div class="w-full sm:w-1/2 border box-border">
                <div class="ocr-text border">
                    <div class="text-xs sm:text-base p-4 rounded-lg bg-gray-700 text-green-500 h-full">
                    @if(session('ocr_text'))
                        <pre> {{session('ocr_text')}}</pre>
                    @endif
                    </div>
                </div>

                <!-- Correction Form Column -->
                <div class="py-1">
                    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
                        <div class="w-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
                        @if(session('total'))
                            <strong>Total is: </strong>
                            @foreach( session('total') as $total)
                                {{$total}}
                            @endforeach
                        @endif 
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-admin-layout>