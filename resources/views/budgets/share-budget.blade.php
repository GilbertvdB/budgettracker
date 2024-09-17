<x-app-layout>
<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="w-full text-center bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
        <strong>{{$budget->title}}</strong>    
        <p>Enter e-mail to share this budget.</p>
        <form method="POST" action="{{ route('budgets.shareBudget', $budget) }}">
            @csrf
            <!-- Email Address -->
            <div class="mt-1">
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-secondary-button class="ms-4">
                <a href="{{ route('dashboard') }}">
                    {{ __('Cancel') }}
                </a>
                </x-secondary-button>
                <x-primary-button class="ms-4">
                    {{ __('Send Invitation') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>