<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col p-6 text-gray-900 dark:text-gray-100">
                    @if(Auth::user()->active_budget)
                        <span>{{ Auth::user()->active_budget->title }}</span>
                        <span>Set: {{ Auth::user()->active_budget->amount }}</span>
                        @php
                            // Calculate the percentage of the remaining amount
                            $budget = Auth::user()->active_budget;
                            $percentage = ($budget->rest_amount / $budget->amount) * 100;
                        @endphp
            
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 rounded-full h-4 mt-2">
                            <div class="bg-green-500 h-4 rounded-full" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <span class="text-xl mt-2">Available: {{ Auth::user()->active_budget->rest_amount }}</span>
                    @else
                        <span>No budgets to display.</span>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                @if(Auth::user()->active_budget)
                <form action="{{ route('upload.receipt') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="receipt" class="block text-gray-700 text-sm font-bold mb-2">
                            Upload Receipt:
                        </label>
                        <input type="file" name="receipt" id="receipt" accept="image/*" capture="environment" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Upload Receipt
                        </button>
                    </div>
                </form>
                @else
                    <p>Set a Budget to begin uploading receipts.</p>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
