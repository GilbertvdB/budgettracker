<x-admin-layout>

<div class="max-w-6xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
    <div class="container flex border">
        <div class="left w-1/2 border box-border">
            <div class="receipt-image border">
                <img src="{{ asset($review->image_url) }}" alt="receipt-img">
            </div>
        </div>
        <div class="right flex flex-col w-1/2 border box-border">
            <div class="ocr-text border">
                <div class="p-4 rounded-lg bg-gray-700 text-green-500 h-full">
                    <pre>{{ $review->ocr_text }}</pre>
                </div>
            </div>
            <div class="correction-box border">
                <div class="py-1">
                    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
                        <div class="w-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
                            <strong>Edit expense:</strong>
                            <!-- <div class="p-6 text-gray-900 dark:text-gray-100"><.div> -->
                            <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                            @csrf
                            @method('patch')
                                <!-- Total -->
                                <div class="mt-2">
                                    <x-input-label for="total" :value="__('Total')" />
                                    <x-text-input id="total" class="block mt-1 w-full" type="number" name="total" min="1" step="0.01" :value="old('total', $review->total)" placeholder="ie 134,56" required autocomplete="total" />
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
</div>
</x-admin-layout>