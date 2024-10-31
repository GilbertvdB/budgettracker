<x-app-layout>
<div class="py-1">
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full bg-white dark:bg-gray-800 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
            <strong>Create an expense:</strong>
            <!-- <div class="p-6 text-gray-900 dark:text-gray-100"><.div> -->
            <form method="POST" action="{{ route('expenses.store') }}">
            @csrf
                <!-- Budget id -->
                <input type="hidden" name="budget_id" value="{{ $budget->id }}">

                <!-- Name -->
                <div class="mt-2">
                    <x-input-label for="name" :value="__('Expense Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" placeholder="ie Ah, Etos" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Total -->
                <div class="mt-4">
                    <x-input-label for="total" :value="__('Expense Total')" />
                    <x-text-input id="total" class="block mt-1 w-full" type="number" name="total" min="1" step="0.01" :value="old('total')" placeholder="ie 134,56" required autocomplete="total" />
                    <x-input-error :messages="$errors->get('total')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button class="ms-4">
                    <a href="{{ route('expenses.show', $budget->id) }}">
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
