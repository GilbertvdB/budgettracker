<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expenses') }}
            </h2>
            <a href="{{ url()->previous() }}" class="border bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full bg-white rounded-xl shadow-lg p-4 mb-4">
            <div class="flex justify-between">
                <span>Expenses for <strong>{{ $budget->title }}</strong></span>
                <span>EUR</span>
            </div>
        </div>

        <div class="container mx-auto mb-4 space-y-3">
            @foreach ($expenses as $expense)
                <div class="expense w-full bg-white rounded-xl shadow-lg p-4">
                    <div class="flex flex-col">
                        <span>{{ $expense->updated_at->format('d F') }}</span>
                        <div class="flex justify-between pt-2">
                            <img src="https://placehold.co/30" alt="receipt-icon">
                            <span>{{ $expense->total }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
