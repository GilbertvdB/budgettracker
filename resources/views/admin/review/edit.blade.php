<x-admin-layout>
    <div class="max-w-6xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <div class="container flex flex-col sm:flex-row sm:flex-wrap border">
            <!-- Left Column (Receipt Image) -->
            <div class="w-full sm:w-1/2 border box-border">
                <div class="receipt-image border">
                    <img src="{{ asset($review->receipt->url) }}" alt="receipt-img" class="w-full">
                </div>
            </div>

            <!-- OCR Text Column -->
            <div class="w-full sm:w-1/2 border box-border">
                <div class="ocr-text border">
                    <div class="text-xs sm:text-base p-4 rounded-lg bg-gray-700 text-green-500 h-full">
                        <pre>{{ $review->receipt->ocr_text }}</pre>
                    </div>
                </div>

                <!-- Correction Form Column -->
                <div class="py-1">
                    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
                        <div class="w-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
                            <strong>Edit expense:</strong>
                            <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                                @csrf
                                @method('patch')
                                <!-- Total -->
                                <div class="mt-2">
                                    <x-input-label for="total" :value="__('Total')" />
                                    <x-text-input id="total" class="block mt-1 w-full" type="number" name="total" min="1" step="0.01" :value="old('total', $review->receipt->total)" placeholder="ie 134,56" required autocomplete="total" />
                                    <x-input-error :messages="$errors->get('total')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <x-secondary-button class="ms-4">
                                        <a href="{{ url()->previous() }}">
                                            {{ __('Cancel') }}
                                        </a>
                                    </x-secondary-button>
                                    <x-primary-button class="ms-4">
                                        {{ __('Update') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-admin-layout>
