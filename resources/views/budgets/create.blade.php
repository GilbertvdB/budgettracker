<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Budgets Create') }}
            </h2>
            <a href="{{ route('budgets.index') }}" class="border bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                <form method="POST" action="{{ route('budgets.store') }}">
                    @csrf

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Budget Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" placeholder="ie Budget 1" required autofocus autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Amount -->
                    <div class="mt-4">
                        <x-input-label for="amount" :value="__('Budget amount')" />
                        <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" min="1" step="0.01" :value="old('amount')" placeholder="ie 134,56" required autocomplete="amount" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Create') }}
                        </x-primary-button>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
