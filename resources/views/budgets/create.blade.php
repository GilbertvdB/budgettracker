<x-app-layout>
<div class="py-1">
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full bg-white dark:bg-gray-800 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
            <strong>Create a budget:</strong>
            <!-- <div class="p-6 text-gray-900 dark:text-gray-100"><.div> -->
            <form method="POST" action="{{ route('budgets.store') }}">
            @csrf
                <!-- Title -->
                <div class="mt-2">
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
                    <x-secondary-button class="ms-4">
                    <a href="{{ route('dashboard') }}">
                        {{ __('Cancel') }}
                    </a>
                    </x-secondary-button>
                    <x-primary-button class="ms-4">
                        {{ __('Create') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
