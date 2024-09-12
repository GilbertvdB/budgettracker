<x-app-layout>
<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="w-full bg-white rounded-xl shadow-lg p-4 mb-4">
        <strong>{{$budget->title}}</strong>    
        <p>Enter an e-mail below to invite a user to share this budget together.</p>
        <form method="POST" action="{{ route('budgets.shareBudget', $budget) }}">
            @csrf
            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Send Share Invitation') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>